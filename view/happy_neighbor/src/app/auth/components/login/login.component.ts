import { Component, OnInit } from '@angular/core';
import { LoginService } from '../../services/login.service';
import { FormGroup, FormControl, Validators} from '@angular/forms';

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
      Validators.pattern(/^[\w]+( [\w]+)*$/)
    ]),
    lastName: new FormControl('',[
      Validators.minLength(2),
      Validators.maxLength(80),
      Validators.pattern(/^[\w]+( [\w]+)*$/)
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

  constructor(private loginService: LoginService) { }

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
  login() {
    if (this.formLogin.status == "VALID") {
      this.loginService.login(
        this.formLogin.value.mail,
        this.formLogin.value.passUser
      );
    }
  }

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
          if (responseSignUp == 0 || responseSignUp == 1) {
            resultSignUp = parseInt(responseSignUp.toString())
          }
          if (resultSignUp) {
            this.loginService.login(
              this.formSignUp.value.mailSignUp,
              this.formSignUp.value.passUserSignUp
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
