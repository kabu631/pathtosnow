<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch } from 'vue'

const props = defineProps({ orders: Object })
const statusFilter  = ref('')
const paymentFilter = ref('')

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
const statusIcons = {
  pending:'⏳', confirmed:'✅', processing:'⚙️', shipped:'📦', delivered:'🏠', cancelled:'❌', refunded:'↩️',
}

watch([statusFilter, paymentFilter], () => {
  const params = new URLSearchParams()
  if (statusFilter.value)  params.set('status', statusFilter.value)
  if (paymentFilter.value) params.set('payment', paymentFilter.value)
  window.location.href = `/admin/orders${params.toString() ? '?' + params.toString() : ''}`
})

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—'
}

const pendingCount   = computed => props.orders.data.filter(o => o.status === 'pending').length
const unpaidCount    = computed => props.orders.data.filter(o => o.payment_status === 'unpaid').length
const deliveredCount = computed => props.orders.data.filter(o => o.status === 'delivered').length
</script>

<template>
  <Head title="Orders — Admin | PathToSnow Nepal" />
  <AdminLayout>

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Shop Orders</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ orders.total }} total orders</p>
      </div>
      <div class="flex gap-3 flex-wrap">
        <select v-model="statusFilter" class="border border-gray-200 bg-white text-gray-700 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-300 w-44" id="filter-status">
          <option value="">All Statuses</option>
          <option value="pending">⏳ Pending</option>
          <option value="confirmed">✅ Confirmed</option>
          <option value="processing">⚙️ Processing</option>
          <option value="shipped">📦 Shipped</option>
          <option value="delivered">🏠 Delivered</option>
          <option value="cancelled">❌ Cancelled</option>
          <option value="refunded">↩️ Refunded</option>
        </select>
        <select v-model="paymentFilter" class="border border-gray-200 bg-white text-gray-700 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-300 w-40" id="filter-payment">
          <option value="">All Payments</option>
          <option value="unpaid">💳 Unpaid</option>
          <option value="paid">✅ Paid</option>
          <option value="refunded">↩️ Refunded</option>
        </select>
      </div>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Total Orders</p>
        <p class="text-2xl font-black text-gray-900">{{ orders.total }}</p>
      </div>
      <div class="bg-amber-50 rounded-xl border border-amber-200 p-4 shadow-sm">
        <p class="text-xs text-amber-700 mb-1">⏳ Pending</p>
        <p class="text-2xl font-black text-amber-700">
          {{ orders.data.filter(o => o.status === 'pending').length }}
        </p>
      </div>
      <div class="bg-rose-50 rounded-xl border border-rose-200 p-4 shadow-sm">
        <p class="text-xs text-rose-700 mb-1">💳 Unpaid</p>
        <p class="text-2xl font-black text-rose-700">
          {{ orders.data.filter(o => o.payment_status === 'unpaid').length }}
        </p>
      </div>
      <div class="bg-green-50 rounded-xl border border-green-200 p-4 shadow-sm">
        <p class="text-xs text-green-700 mb-1">🏠 Delivered</p>
        <p class="text-2xl font-black text-green-700">
          {{ orders.data.filter(o => o.status === 'delivered').length }}
        </p>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-100 bg-gray-50">
          <tr>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Order</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Customer</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Items</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Total</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Order Status</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Payment</th>
            <th class="text-left px-4 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Date</th>
            <th class="px-4 py-3.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="order in orders.data" :key="order.id"
              class="hover:bg-gray-50 transition-colors group">

            <td class="px-4 py-3.5">
              <span class="font-mono text-orange-600 text-xs font-bold">{{ order.order_number }}</span>
            </td>

            <td class="px-4 py-3.5">
              <p class="font-medium text-gray-900 text-sm">{{ order.customer_name }}</p>
              <p class="text-gray-400 text-xs">{{ order.customer_email }}</p>
            </td>

            <td class="px-4 py-3.5">
              <div class="space-y-0.5">
                <p v-for="item in order.items?.slice(0, 2)" :key="item.id"
                   class="text-xs text-gray-600 truncate max-w-[160px]">
                  {{ item.quantity }}× {{ item.product_name }}
                </p>
                <p v-if="order.items?.length > 2" class="text-xs text-gray-400">
                  +{{ order.items.length - 2 }} more
                </p>
              </div>
            </td>

            <td class="px-4 py-3.5">
              <span class="text-green-700 font-bold">${{ Number(order.total).toFixed(2) }}</span>
            </td>

            <td class="px-4 py-3.5">
              <span :class="['inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold', statusStyles[order.status] ?? 'bg-gray-100 text-gray-700']">
                {{ statusIcons[order.status] }} {{ order.status }}
              </span>
            </td>

            <td class="px-4 py-3.5">
              <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', paymentStyles[order.payment_status] ?? 'bg-rose-100 text-rose-800']">
                {{ order.payment_status ?? 'unpaid' }}
              </span>
            </td>

            <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap">{{ fmt(order.created_at) }}</td>

            <td class="px-4 py-3.5">
              <Link :href="`/admin/orders/${order.id}`"
                    class="text-orange-600 hover:text-orange-800 text-xs font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                View →
              </Link>
            </td>
          </tr>

          <tr v-if="!orders.data.length">
            <td colspan="8" class="text-center py-16 text-gray-400">
              <p class="text-3xl mb-3">📭</p>
              <p>No orders found.</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="orders.last_page > 1" class="flex justify-center gap-2 mt-6">
      <Link v-for="link in orders.links" :key="link.label" :href="link.url ?? '#'"
            :class="['px-4 py-2 rounded-lg text-sm font-medium border', link.active ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600 border-gray-200 hover:border-orange-300', !link.url && 'opacity-40 pointer-events-none']"
            v-html="link.label" />
    </div>

  </AdminLayout>
</template>
