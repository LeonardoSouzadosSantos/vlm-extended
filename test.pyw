import sys
if sys.version_info[0] == 2:
    import Tkinter
    tkinter = Tkinter
else:
    import tkinter
from PIL import Image, ImageTk

def showPIL(pilImage):
    root = tkinter.Tk()
    w, h = root.winfo_screenwidth(), root.winfo_screenheight()
    root.overrideredirect(1)
    root.geometry("%dx%d+0+0" % (w, h))
    root.focus_set()    
    root.attributes("-alpha", 0.5)
    root.bind("<Escape>", lambda e: (e.widget.withdraw(), e.widget.quit()))
    canvas = tkinter.Canvas(root,width=w,height=h,highlightthickness=0)
    canvas.pack()
    canvas.configure(background='black')
    root.wm_attributes("-transparentcolor", "black")
    root.overrideredirect(1)
    imgWidth, imgHeight = pilImage.size
    if imgWidth > w or imgHeight > h:
        ratio = min(w/imgWidth, h/imgHeight)
        imgWidth = int(imgWidth*ratio)
        imgHeight = int(imgHeight*ratio)
        pilImage = pilImage.resize((imgWidth,imgHeight), Image.ANTIALIAS)
    image = ImageTk.PhotoImage(pilImage)
    imagesprite = canvas.create_image(w/2,h/2,image=image)
    root.call('wm', 'attributes', '.', '-topmost', '1')
    param_1= sys.argv[2]
    root.after(param_1, lambda: root.destroy())
    root.mainloop()
param_2= sys.argv[1]
pilImage = Image.open(param_2)
showPIL(pilImage)