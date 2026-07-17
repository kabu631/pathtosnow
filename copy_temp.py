import shutil
import os

src = r"C:\Users\kabin\.gemini\antigravity-ide\brain\a399c9f1-0e5f-423a-9a69-cfa578f0a535\.system_generated\steps\280\content.md"
dst = "reference.html"

if os.path.exists(src):
    shutil.copy(src, dst)
    print("Copied content.md to reference.html successfully")
else:
    print("Source content.md not found!")
