//import watermark from './utils/watermark';

window.addEventListener('DOMContentLoaded', () => {
    //watermark()

    const form = document.getElementById("your-profile") as HTMLFormElement;
    form.encoding = "multipart/form-data";
    form.setAttribute("enctype", "multipart/form-data");
});