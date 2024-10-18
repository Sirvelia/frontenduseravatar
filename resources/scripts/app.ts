//import watermark from './utils/watermark';

window.addEventListener('DOMContentLoaded', () => {
    //watermark()

    const form = document.getElementById("your-profile") as HTMLFormElement|null
    if (form) {
        form.encoding = "multipart/form-data"
        form.setAttribute("enctype", "multipart/form-data")
    }

    document.getElementById('fua_avatar_switch_button')?.addEventListener('click', function() {
        document.getElementById('fua_avatar_input')?.click()
    })
    
    document.getElementById('fua_avatar_input')?.addEventListener('change', function(event: Event) {
        const target = event.target as HTMLInputElement
        
        if (target.files && target.files[0]) {
            const file = target.files[0]
            const blobUrl = URL.createObjectURL(file)
            const imagePreview = document.getElementById("fua_avatar_preview") as HTMLImageElement
            imagePreview.src = blobUrl
            imagePreview.onload = () => URL.revokeObjectURL(blobUrl)

            const saveButton = document.getElementById("fua_avatar_submit") as HTMLButtonElement
            saveButton.disabled = false;
        }
    })
});