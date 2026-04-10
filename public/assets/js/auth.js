document.addEventListener("DOMContentLoaded",()=>{
    const form = document.querySelectorAll(".auth-form");

    form.forEach(form=>{
        form.addEventListener("submit", e=>{
            const email = form.querySelector("input[name='email']");
            const password = form.querySelector("input[name='password']");
            if(!email || !email.value.trim()){
               alert("Email is Required");
               e.preventDefault();
               return;
            }
            if(!password || password.value.length < 6){
               alert("Password must be at least 6 characters");
               e.preventDefault();
               return;
            }
        })
    })
})