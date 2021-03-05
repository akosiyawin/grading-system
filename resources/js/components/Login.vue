<template>
  <div class="myContainer">
    <div class="myFormsContainer">
      <div class="mySignIn">
        <form class="mySignInForm" @submit.prevent="submit">
          <img :src="logo" class="mb-3 animate__animated animate__bounceIn schoolLogo" width="150px" alt="">
          <h2 class="myTitle animate__animated animate__heartBeat animate__delay-2s	">EPCST GRADE BOOK</h2>
          <span class="pl-3 text-danger font-weight-bold animate__headShake animate__animated animate__infinite animate__slow" v-if="form.errors.has('username')" v-text="form.errors.get('username')"></span>
          <div class="myInputField">
            <i class="fas fa-user"></i>
            <input type="text" required v-model="form.username" placeholder="Username">
          </div>
          <span class="pl-3 text-danger font-weight-bold" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>
          <div class="myInputField">
            <i class="fas fa-lock"></i>
            <input type="password" required v-model="form.password" placeholder="Password">
          </div>
          <input type="submit" value="Sign in" class="myBtn mySolid animate__swing animate__animated animate__delay-3s">
          <p class="mySocialText">Visit our Social Media Platforms</p>
          <div class="mySocialMedia">
            <a href="https://www.facebook.com/EPCST" target="_blank" class="mySocialIcon animate__animated animate__infinite animate__pulse">
              <i class="fab fa-facebook-f animate__animated animate__infinite animate__pulse"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCGQMVO8_CpGE_a4zVAgO50g" target="_blank" class="mySocialIcon animate__animated animate__infinite animate__pulse">
              <i class="fab fa-youtube animate__animated animate__infinite animate__pulse"></i>
            </a>
            <a href="https://epcst.edu.ph/moodle/login/index.php" target="_blank" class="mySocialIcon animate__animated animate__infinite animate__pulse">
              <i class="fas fa-graduation-cap animate__animated animate__infinite animate__pulse"></i>
            </a>
            <a href="https://ched.gov.ph/eastwoods-professional-college-science-technology-inc-profile/" target="_blank" class="mySocialIcon animate__animated animate__infinite animate__pulse">
              <i class="fas fa-school animate__animated animate__infinite animate__pulse"></i>
            </a>
          </div>
        </form>
      </div>
    </div>
    <div class="myPanelsContainer">
      <div class="myPanel myLeftPanel">
        <div class="myContent">
          <h3 class="animate__fadeIn animate__animated">Need help ?</h3>
          <p class="animate__fadeIn animate__animated">You can contact our Technical Assistance Team, they are extremely responsive for all of your concerns.</p>
          <button @click="toAssistance" class="myBtn myTransparent animate__animated animate__infinite animate__pulse" id="mySignUpBtn">CONTACT US</button>
        </div>
        <img :src="icon" class="myImage" alt="icon_help">
      </div>
    </div>
    <loading></loading>
  </div>
</template>

<script>
import api from "../tools/api";
import Form from "../tools/form";
import logo from "../logo_school.png"
import icon from "../icon_help.svg"

export default {
  name: "Login",
  data: () => ({
    valid: true,
    loading: false,
    form: new Form({
      username: '',
      password: ''
    }),
    logo,
    icon,
  }),
  methods: {
    submit() {
      this.form.post(api.login)
      .then(r => {
        location.replace('/handler')
      })
      .catch(err => {
        this.form.errors.set(err.errors)
      })
    },
    toAssistance(){
      window.open('https://www.facebook.com/eastwoodstech','_blank')
    }
  }
}
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");
.v-messages__message {
  color: red !important;
  font-weight: 500;
}
</style>

<style scoped>
*{
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}
body, input{
  font-family: 'Poppins', sans-serif;
}
.myContainer{
  position: relative;
  width: 100%;
  min-height: 100vh;
  /*Change*/
  overflow: hidden;
  background: linear-gradient(rgba(236, 240, 241, .8), rgba(44, 62, 80, .9)),  no-repeat url("https://epcst.files.wordpress.com/2012/08/eastwoods-3.jpg");
}
.schoolLogo{
  display: block;
}
.myContainer:before{
  content: '';
  position: absolute;
  width: 2000px;
  height: 2000px;
  border-radius: 50%;
  background: linear-gradient(-45deg,#86C127,#4481eb); /*Change*/
  top: -10%;
  right: 48%;
  transition: .5s;
  transform: translateY(-50%); /*Change*/
}
.myFormsContainer{
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}
.mySignIn{
  position: absolute;
  top: 50%;
  left: 75%;
  transform: translate(-50%,-50%);
  width: 50%;
  z-index: 5;
}
form{
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  overflow: hidden;
}
.myTitle{
  font-size: 2.2rem;
  /*color: #444;*/
  margin-bottom: 10px;
  color: #fff;
}
.myInputField{
  max-width: 380px;
  width: 100%;
  height: 55px;
  background-color: #f0f0f0;
  margin: 10px 0;
  border-radius: 55px;
  display: grid;
  grid-template-columns: 15% 85%;
  padding: 0 .4rem;
}
.myInputField i {
  text-align: center;
  line-height: 55px;
  color: #acacac;
  font-size: 1.1rem;
}
.myInputField input {
  background: none;
  outline: none;
  border: none;
  line-height: 1;
  font-weight: 600;
  font-size: 1.1rem;
  color: #333;
}
.myInputField input::placeholder{
  color: #aaa;
  font-weight: 500;
}
.myBtn {
  width: 150px;
  height: 49px;
  border: none;
  outline: none;
  border-radius: 49px;
  cursor: pointer;
  background-color: #86C127; /*Change*/
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  margin: 10px 0;
  transition: .5s;
}
.myBtn:hover{
  background-color: #6FA814; /*Change*/
}
.mySocialText{
  padding: .7rem 0 ;
  font-size: 1rem;
  color: #fff;
}
.mySocialMedia{
  display: flex;
  justify-content: center;
}
.mySocialIcon{
  height: 46px;
  width: 46px;
  /*border: 1px solid #333;*/
  border: 1px solid #fff;
  margin: 0 .45rem;
  display: flex;
  justify-content: center;
  align-items: center;
  text-decoration: none;
  /*color: #333;*/
  font-size: 1.1rem;
  border-radius: 50%;
  transition: .3s;
  color: #fff;

}
.mySocialIcon:hover{
  color: #86C127;
  border-color: #6FA814;
}
.myPanelsContainer{
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  display: grid;
  grid-template-columns: repeat(2,1fr);
}
.myPanel{
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-around;
  text-align: center;
}
.myLeftPanel{
  padding: 3rem 17% 2rem 12%;
}
.myPanel .myContent{
  color: #fff;
}
.myPanel h3 {
  font-weight: 600;
  line-height: 1;
  font-size: 1.5rem;
}
.myPanel p {
  font-size: .95rem;
  padding: .7rem 0;
}
.myBtn.myTransparent{
  margin: 0;
  background: none;
  border: 2px solid;
  width: 130px;
  height: 41px;
  font-weight: 600;
  font-size: .8rem;
}
.myImage{
  width: 100%;
}

@media(max-width: 870px) {
  .myContainer{
    min-height: 800px;
    height: 100vh;
    background-position: top;
  }
  .myContainer:before{
    width: 1500px;
    height: 1500px;
    left: 30%;
    bottom: 68%;
    transform: translateX(-50%);
    right: initial;
    top: initial;
    transition: 2s ease-in-out;
  }
  .mySignIn{
    width: 100%;
    left: 50%;
    top: 95%;
    transform: translate(-50%,-100%);
    transition: 1s .1s ease-in-out;
  }
  .myPanelsContainer{
    grid-template-columns: 1fr;
    grid-template-rows: 1fr 2fr 1fr;
    z-index: 1;
  }
  .myPanel{
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
  }
  .myPanel .myContent{
    padding-right: 15%;
  }
  .myPanel p {
    font-size: .8rem;
    padding: .5rem 0;
  }
  .myBtn.myTransparent{
    width: 110px;
    height: 35px;
    font-size: .7rem;
  }
  .myPanel h3 {
    font-size: 1.2rem;
  }
  .myImage{
    width: 200px;
    transition: .9s .8s ease-in-out;
  }
  .myLeftPanel{
    grid-row: 1/2;
    padding: 2.5rem 8%;
  }
}

@media (max-width: 570px) {
  form{
    padding: 0 1.5rem;
  }
  .myImage{
    display: none;
  }
  .myPanel .myContent{
    padding: .5rem 1rem;
  }
  .myContainer:before{
    bottom: 72%;
    left: 50%;
  }
  .schoolLogo{
    width: 100px;
  }
  .myTitle{
    font-size: 1.8rem;
  }
}
</style>
