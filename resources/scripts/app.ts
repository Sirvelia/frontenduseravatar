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

    document.getElementById('fua_avatar_delete')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete your current avatar?')) {
            // Clear the file input before submitting
            const fileInput = document.getElementById('fua_avatar_input') as HTMLInputElement
            fileInput.value = ''
            const imagePreview = document.getElementById("fua_avatar_preview") as HTMLImageElement
            imagePreview.src = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='
        } else {
            // Prevent form submission if user cancels
            return false
        }
    })
});