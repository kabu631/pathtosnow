<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ order: Object })

const statusForm  = useForm({ status: props.order.status })
const paymentForm = useForm({ payment_status: props.order.payment_status ?? 'unpaid' })

const statusStyles = {
  pending:    'bg-amber-100 text-amber-800 border border-amber-200',
  confirmed:  'bg-blue-100 text-blue-800 border border-blue-200',
  processing: 'bg-indigo-100 text-indigo-800 border border-indigo-200',
  shipped:    'bg-cyan-100 text-cyan-800 border border-cyan-200',
  delivered:  'bg-green-100 text-green-800 border border-green-200',
  cancelled:  'bg-red-100 text-red-800 border border-red-200',
  refunded:   'bg-gray-100 text-gray-700 border border-gray-200',
}
const paymentStyles = {
  unpaid:   'bg-rose-100 text-rose-800 border border-rose-200',
  paid:     'bg-emerald-100 text-emerald-800 border border-emerald-200',
  refunded: 'bg-gray-100 text-gray-700 border border-gray-200',
}

const statusSteps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered']
const currentStep = (s) => statusSteps.indexOf(s)

function updateStatus() {
  statusForm.patch(`/admin/orders/${props.order.id}/status`)
}
function updatePayment() {
  paymentForm.patch(`/admin/orders/${props.order.id}/payment`)
}
function fmt(d, time = false) {
  if (!d) return '—'
  const opts = { day: 'numeric', month: 'short', year: 'numeric' }
  if (time) { opts.hour = '2-digit'; opts.minute = '2-digit' }
  return new Date(d).toLocaleDateString('en-GB', opts)
}
function deleteOrder() {
  if (!confirm(`Permanently delete order ${props.order.order_number}? This cannot be undone.`)) return
  router.delete(`/admin/orders/${props.order.id}`)
}
</script>

<template>
  <Head :title="`Order ${order.order_number} — Admin | PathToSnow Nepal`" />
  <AdminLayout>

    <!-- Back + title row -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
      <Link href="/admin/orders" class="text-gray-500 hover:text-gray-800 text-sm flex items-center gap-1">
        ← Orders
      </Link>
      <h1 class="text-xl font-bold text-gray-900">{{ order.order_number }}</h1>
      <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold', statusStyles[order.status] ?? 'bg-gray-100 text-gray-700']">
        {{ order.status }}
      </span>
      <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold', paymentStyles[order.payment_status] ?? 'bg-rose-100 text-rose-800']">
        💳 {{ order.payment_status ?? 'unpaid' }}
      </span>
    </div>

    <!-- Progress bar -->
    <div v-if="!['cancelled','refunded'].includes(order.status)"
         class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
      <p class="text-xs text-gray-400 mb-4 font-semibold uppercase tracking-wider">Order Progress</p>
      <div class="flex items-center">
        <template v-for="(step, idx) in statusSteps" :key="step">
          <div class="flex flex-col items-center">
            <div :class="['w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all',
                          idx <= currentStep(order.status)
                            ? 'bg-emerald-600 border-emerald-500 text-white shadow-sm'
                            : 'bg-white border-gray-300 text-gray-400']">
              {{ idx < currentStep(order.status) ? '✓' : idx + 1 }}
            </div>
            <span class="text-xs mt-1.5 capitalize font-medium"
                  :class="idx <= currentStep(order.status) ? 'text-emerald-700' : 'text-gray-400'">
              {{ step }}
            </span>
          </div>
          <div v-if="idx < statusSteps.length - 1"
               :class="['flex-1 h-0.5 mx-1 mb-5 rounded', idx < currentStep(order.status) ? 'bg-emerald-500' : 'bg-gray-200']"/>
        </template>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- LEFT: Items + customer -->
      <div class="lg:col-span-2 space-y-5">

        <!-- Order Items card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h2 class="font-bold text-gray-900">Order Items</h2>
            <span class="text-xs text-gray-500 font-medium">{{ order.items?.length ?? 0 }} item(s)</span>
          </div>

          <div class="divide-y divide-gray-100">
            <div v-for="item in order.items" :key="item.id"
                 class="flex items-center gap-4 px-6 py-4">
              <img v-if="item.product_image" :src="item.product_image" :alt="item.product_name"
                   class="w-16 h-16 object-cover rounded-xl flex-shrink-0 border border-gray-200"/>
              <div v-else
                   class="w-16 h-16 bg-gray-100 rounded-xl flex-shrink-0 flex items-center justify-center text-2xl border border-gray-200">
                🎒
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 text-sm">{{ item.product_name }}</p>
                <p class="text-gray-500 text-xs mt-0.5">{{ item.quantity }} × ${{ Number(item.unit_price).toFixed(2) }}</p>
              </div>
              <p class="text-green-700 font-bold text-sm flex-shrink-0">${{ Number(item.total_price).toFixed(2) }}</p>
            </div>
            <div v-if="!order.items?.length" class="px-6 py-8 text-center text-gray-400 text-sm">
              No items found
            </div>
          </div>

          <!-- Totals -->
          <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 space-y-2">
            <div class="flex justify-between text-sm text-gray-500">
              <span>Subtotal</span>
              <span>${{ Number(order.subtotal).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
              <span>Shipping</span>
              <span>{{ Number(order.shipping_cost) === 0 ? 'Free' : '$' + Number(order.shipping_cost).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
              <span>Total</span>
              <span class="text-green-700">${{ Number(order.total).toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Customer info -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 class="font-bold text-gray-900 mb-4">Customer Information</h2>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-medium">Name</p>
              <p class="text-gray-800 font-medium">{{ order.customer_name }}</p>
            </div>
            <div>
              <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-medium">Email</p>
              <p class="text-gray-800">{{ order.customer_email }}</p>
            </div>
            <div v-if="order.customer_phone">
              <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-medium">Phone</p>
              <p class="text-gray-800">{{ order.customer_phone }}</p>
            </div>
            <div v-if="order.user">
              <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-medium">Account</p>
              <p class="text-gray-800">{{ order.user.name }}</p>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT: Actions + Shipping + Timeline -->
      <div class="space-y-5">

        <!-- Update Order Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span>📋</span> Order Status
          </h2>
          <select v-model="statusForm.status" class="w-full border border-gray-300 bg-white text-gray-800 text-sm rounded-lg px-3 py-2.5 mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-300" id="update-order-status">
            <option value="pending">⏳ Pending</option>
            <option value="confirmed">✅ Confirmed</option>
            <option value="processing">⚙️ Processing</option>
            <option value="shipped">📦 Shipped</option>
            <option value="delivered">🏠 Delivered</option>
            <option value="cancelled">❌ Cancelled</option>
            <option value="refunded">↩️ Refunded</option>
          </select>
          <button @click="updateStatus" :disabled="statusForm.processing"
                  class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors" id="btn-update-status">
            {{ statusForm.processing ? 'Saving…' : 'Update Order Status' }}
          </button>
        </div>

        <!-- Update Payment Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span>💳</span> Payment Status
          </h2>
          <div class="mb-3">
            <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold', paymentStyles[order.payment_status] ?? 'bg-rose-100 text-rose-800']">
              Current: {{ order.payment_status ?? 'unpaid' }}
            </span>
          </div>
          <select v-model="paymentForm.payment_status" class="w-full border border-gray-300 bg-white text-gray-800 text-sm rounded-lg px-3 py-2.5 mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-300" id="update-payment-status">
            <option value="unpaid">💳 Unpaid</option>
            <option value="paid">✅ Paid</option>
            <option value="refunded">↩️ Refunded</option>
          </select>
          <button @click="updatePayment" :disabled="paymentForm.processing"
                  class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors" id="btn-update-payment">
            {{ paymentForm.processing ? 'Saving…' : 'Update Payment Status' }}
          </button>
          <p v-if="order.paid_at" class="text-xs text-emerald-600 mt-2 text-center font-medium">
            ✅ Paid on {{ fmt(order.paid_at, true) }}
          </p>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span>📍</span> Shipping Address
          </h2>
          <div class="text-gray-700 text-sm space-y-1 leading-relaxed">
            <p class="font-semibold text-gray-900">{{ order.shipping_name }}</p>
            <p>{{ order.shipping_address }}</p>
            <p>{{ order.shipping_city }}{{ order.shipping_postal_code ? ', ' + order.shipping_postal_code : '' }}</p>
            <p>{{ order.shipping_country }}</p>
          </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span>🕐</span> Timeline
          </h2>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-500">Ordered</span>
              <span class="text-gray-800 font-medium">{{ fmt(order.created_at, true) }}</span>
            </div>
            <div v-if="order.paid_at" class="flex justify-between">
              <span class="text-gray-500">Paid</span>
              <span class="text-emerald-700 font-medium">{{ fmt(order.paid_at, true) }}</span>
            </div>
            <div v-if="order.shipped_at" class="flex justify-between">
              <span class="text-gray-500">Shipped</span>
              <span class="text-cyan-700 font-medium">{{ fmt(order.shipped_at, true) }}</span>
            </div>
            <div v-if="order.delivered_at" class="flex justify-between">
              <span class="text-gray-500">Delivered</span>
              <span class="text-green-700 font-medium">{{ fmt(order.delivered_at, true) }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-100 pt-2 mt-1">
              <span class="text-gray-500">Payment method</span>
              <span class="text-gray-800 font-medium capitalize">{{ order.payment_method ?? 'COD' }}</span>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-3">Danger Zone</h2>
          <button @click="deleteOrder"
                  class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold py-2.5 rounded-xl text-sm transition-colors">
            🗑 Delete Order
          </button>
        </div>

      </div>
    </div>

  </AdminLayout>
</template>
