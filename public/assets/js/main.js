const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");
const sidebar = document.querySelector(".sidebar");

const overlay = document.querySelector(".overlay");





if(hamburger && sidebar){
 hamburger.addEventListener("click", () =>{
    hamburger.classList.toggle("active");
    sidebar.classList.toggle("active"); 
    overlay.classList.toggle("active");

 })
}else if(hamburger && navMenu){
  hamburger.addEventListener("click", () =>{
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
  })
}
if(overlay){
    overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    hamburger.classList.remove("active");
    overlay.classList.remove("active");

});
}
document.addEventListener("click",(e)=>{
    if(sidebar && hamburger){
        if(sidebar.classList.contains("active")){
             if(!sidebar.contains(e.target) && !hamburger.contains(e.target)){
                 sidebar.classList.remove("active");
                 hamburger.classList.remove("active");
              }
         }
    }
})

document.querySelectorAll(".nav-item").forEach(n => n.addEventListener("click", ()=>{
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
}))

document.querySelectorAll(".wishlist-form").forEach(form=>{
    form.addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(this);
        let button = this.querySelector(".wishlist-btn");

        fetch(this.action,{
            method:"POST",
            body:formData
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.status === "added"){
                button.classList.add("saved");
            }

            if(data.status === "removed"){
                button.classList.remove('saved');
            }
        })
    })
})

document.querySelectorAll(".cart-form").forEach(form1=>{
    form1.addEventListener("submit", function(e){
        e.preventDefault();
        let formData1 = new FormData(this);
        let button1 = this.querySelector(".cart-btn");

        fetch(this.action,{
            method: "POST",
            body: formData1
        })
        .then(res1=>res1.json())
        .then(data1=>{
            if(data1.status === "added"){
                location.reload();

            }else if(data1.status === "removed"){
                location.reload();

            }else if(data1.status === "error"){
                alert("Please Login to add to add to cart");
            }
        })
    })
})


document.addEventListener("click", function(e) {
    
    const button = e.target.closest(".remove-btn");
    
    if (button) {
        const cartItem = button.closest(".cart-item");
        const carId = cartItem.getAttribute("data-id");

        let formData = new FormData();
        formData.append("car_id", carId);

        fetch("../controllers/cartController.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "removed") {
                cartItem.style.opacity = '0';
                setTimeout(() => {
                    cartItem.remove();
                    location.reload();
                }, 300); 
            }
        })
        .catch(err => console.error("Error:", err));
    }
});

document.addEventListener("DOMContentLoaded", () => {
    // Now the browser is sure the HTML exists
    const checkoutForm = document.querySelector(".out-form");

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function() {
           
            
            const button = this.querySelector(".pay-btn");
            button.innerText = "Processing....";
            button.disabled = true;

            console.log("Form submission intercepted!");

            // Your AJAX/Fetch code goes here
        });
    }
});
// document.addEventListener("DOMContentLoaded",()=>{
//     document.querySelectorAll(".out-form").forEach(form2=>{
//     form2.addEventListener("submit", function(e){
//        e.preventDefault();
//        const button2 = document.querySelector(".pay-btn");
//        button2.innerText = "Processing....";
//        button2.disabled = true;

//        console.log("Form submission intercepted!");

    
//     })
// })
// })