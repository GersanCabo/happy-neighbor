import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from '../../global/constants/address-server';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';


@Injectable({
  providedIn: 'root'
})
export class LoginService {

  private url:string = ADDRESS_SERVER + "LoginController.php";

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
      .post(this.url, formData)
      .subscribe({
        next: (response) => this.loggedIn(response),
        error: (error) => console.log(error)
    });
  }

  sendToken(sessionToken: string) {

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


}
