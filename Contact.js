function validate(){
    let email=document.getElementById("email").value; 
    let name = document.getElementById("name").value;
    let subject = document.getElementById("subject").value;
    let message = document.getElementById("message").value;
   
    let emailRegex= /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;  
   
    let nameRegex= /^[A-Za-z\s]{3,}$/
    
    if(emailRegex.test(email)){
        if(nameRegex.test(name)){

        
        document.getElementById("msg").innerText="Valid!";

        document.getElementById("contact-form").action="homepage.html";
        document.getElementById("contact-form").submit();

    }else{
        document.getElementById('msg').innerText= "Name must be at least 3 letters!";

    }
    }else{
        document.getElementById("msg").innerText="Email is not valid!";
    }

}
