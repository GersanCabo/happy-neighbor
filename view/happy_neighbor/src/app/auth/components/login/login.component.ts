import { Component, OnInit } from '@angular/core';
import { LoginService } from '../../services/login.service';
import { FormGroup, FormControl, Validators} from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  providers: [LoginService]
})
export class LoginComponent implements OnInit {

  loginForm:boolean = true;

  /**
   * Validations of the login form
   */
  formLogin = new FormGroup({
    mail: new FormControl('',[
      Validators.required,
      Validators.minLength(5),
      Validators.maxLength(80),
      Validators.pattern(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)
    ]),
    passUser: new FormControl('',[
      Validators.required,
      Validators.minLength(6),
      Validators.maxLength(20),
      Validators.pattern(/^[\w]+( [\w]+)*$/)
    ])
  });

  formSignUp = new FormGroup({
    nameUser: new FormControl('',[
      Validators.required,
      Validators.minLength(2),
      Validators.maxLength(50),
      Validators.pattern(/^[a-zA-ZÀ-ÿ]+(\s*[a-zA-ZÀ-ÿ]*)*[a-zA-ZÀ-ÿ ]+$/)
    ]),
    lastName: new FormControl('',[
      Validators.minLength(2),
      Validators.maxLength(80),
      Validators.pattern(/^[a-zA-ZÀ-ÿ]+(\s*[a-zA-ZÀ-ÿ]*)*[a-zA-ZÀ-ÿ ]+$/)
    ]),
    mailSignUp: new FormControl('', [
      Validators.required,
      Validators.minLength(5),
      Validators.maxLength(80),
      Validators.pattern(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)
    ]),
    passUserSignUp: new FormControl('', [
      Validators.required,
      Validators.minLength(6),
      Validators.maxLength(20),
      Validators.pattern(/^[\w]+( [\w]+)*$/)
    ])

  });

  constructor(private loginService: LoginService, private router: Router) { }

  ngOnInit(): void {
  }

  get formControls() {
    return this.formLogin.controls;
  }

  get formControlsSignUp() {
    return this.formSignUp.controls;
  }

  /**
   * Log in the user if the log in form is valid
   */
  login(mail: string = this.formLogin.value.mail, password: string = this.formLogin.value.passUser, signUp: boolean = false) {
    if (this.formLogin.status == "VALID" || signUp) {
      this.loginService.login(
        mail,
        password
      ).subscribe({
        next: (response) => {
          if (response) {
            this.loggedIn(response);
          } else {
            alert("Correo o contraseña incorrectos");
          }
          //this.loggedIn(response);
        },
        error: (error) => console.log(error)
      });
    }
  }

  /**
   * Process the log in response, save the session token
   * and redirect to the main page
   *
   * @param response JSON Object with the user session token
   */
  private loggedIn(response: object) {
    sessionStorage.setItem('session_token',response.toString());
    this.router.navigate(['/']);
  }

  /**
   * Sign up the new user if the form is valid
   */
  signUp() {
    if (this.formSignUp.status == "VALID") {
      this.loginService.signUp(
        this.formSignUp.value.nameUser,
        this.formSignUp.value.lastName,
        this.formSignUp.value.mailSignUp,
        this.formSignUp.value.passUserSignUp
      ).subscribe({
        next: (responseSignUp) => {
          let resultSignUp = 0;
          console.log(responseSignUp);
          if (responseSignUp == 0 || responseSignUp == 1) {
            resultSignUp = parseInt(responseSignUp.toString())
          }
          if (resultSignUp) {
            this.login(
              this.formSignUp.value.mailSignUp,
              this.formSignUp.value.passUserSignUp,
              true
            );
          } else {
            alert("Email already in use")
          }
        },
        error: (errorSelectUserCommunities) => console.log(errorSelectUserCommunities)
      });
    }
  }

}
