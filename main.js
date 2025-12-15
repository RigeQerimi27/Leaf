function validate() {

    let email=document.getElementById("email").value;

    let password=document.getElementById("password").value;

    let emailRegex= /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    let passwordRegex= /^(?=.*\d).{8,}$/;

    if(emailRegex.test(email)){

        if(passwordRegex.test(password)){

            document.getElementById("msg").innerText="Valid!";

            document.getElementById("loginForm").action="homepage.html";
            document.getElementById("loginForm").submit();

        } else{

            document.getElementById("msg").innerText=
            "Password must be at least 8 characters and contain a number!";
        }



        }else{

            document.getElementById("msg").innerText="Email is not valid!";
        }
    }



/*slideri te homepage*/

let heroImages = [
  "images/aloevera.jpg",
  "images/hero2.jpg",
  "images/hero3.jpg"
];


let index=0;

function changeHero(step) {


    index = index + step;

    if (index < 0) {
    index = heroImages.length - 1;
  }

    if (index > heroImages.length - 1) {
    index = 0;
  }

   document.getElementById("heroImage").src = heroImages[index];

}




