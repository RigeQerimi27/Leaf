function validate(){
  let email = document.getElementById("email").value; 
  let name = document.getElementById("name").value;
  let subject = document.getElementById("subject").value;
  let message = document.getElementById("message").value;

  let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;  
  let nameRegex = /^[A-Za-z\s]{3,}$/;

  if(!emailRegex.test(email)){
    document.getElementById("msg").innerText = "Email is not valid!";
    return false;
  }

  if(!nameRegex.test(name)){
    document.getElementById("msg").innerText = "Name must be at least 3 letters!";
    return false;
  }

  if(subject.trim() === "" || message.trim() === ""){
    document.getElementById("msg").innerText = "Subject and message are required!";
    return false;
  }

  document.getElementById("msg").innerText = "Valid!";
  return true; 
}

