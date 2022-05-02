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

  constructor(private loginService: LoginService) { }

  ngOnInit(): void {
  }

  get formControls() {
    return this.formLogin.controls;
  }

  login() {
    if (this.formLogin.status == "VALID") {
      this.loginService.login(this.formLogin.value.mail, this.formLogin.value.passUser);
    }
  }
}
