import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from '../../global/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  private url:string = ADDRESS_SERVER + "LoginController.php";

  constructor(private httpClient: HttpClient) { }

  login(mail: string, passUser:string) {
    let formData: FormData = new FormData();
    formData.append("mail",mail);
    formData.append("pass_user",passUser);
    return this.httpClient
      .post(this.url, formData)
      .subscribe({
        next: (response) => this.loggedIn(response),
        error: (error) => console.log(error)
    });
  }

  sendToken(sessionToken: string) {

  }

  private loggedIn(response: object) {
    sessionStorage.setItem('session_token',response.toString());
  }


}
