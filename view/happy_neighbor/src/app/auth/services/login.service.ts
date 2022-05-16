import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from '../../global/constants/address-server';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';


@Injectable({
  providedIn: 'root'
})
export class LoginService {

  private urlLogin:string = ADDRESS_SERVER + "LoginController.php";
  private urlUser:string = ADDRESS_SERVER + "UserController/insert.php";

  constructor(private httpClient: HttpClient, private router: Router) { }

  /**
   * Log in the user into their account
   *
   * @param mail user mail
   * @param passUser user password
   * @returns Observable with the petition
   */
  login(mail: string, passUser:string) {
    let formData: FormData = new FormData();
    formData.append("mail",mail);
    formData.append("pass_user",passUser);
    return this.httpClient
      .post(this.urlLogin, formData)
      .subscribe({
        next: (response) => this.loggedIn(response),
        error: (error) => console.log(error)
    });
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

  signUp(nameUser:string, lastName:string, mail:string, passUser:string) {
    let formData: FormData = new FormData();
    formData.append("action","insert");
    formData.append("name_user", nameUser);
    if (lastName) {
      formData.append("last_name", lastName);
    }
    formData.append("mail", mail);
    formData.append("pass_user",passUser);
    return this.httpClient.post(this.urlUser, formData);
  }


}
