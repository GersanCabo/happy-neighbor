import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class ValidateTokenService {

  private url:string = ADDRESS_SERVER + "UserController/validateToken.php";

  constructor(private httpClient: HttpClient, private router: Router) { }

  /**
   * Validate the user session token and redirect to login
   * if is not
   *
   * @param sessionToken user session token
   * @returns Observer with the petition
   */
  validateToken(sessionToken: string) {
    let formData: FormData = new FormData();
    formData.append("action","validateToken");
    formData.append("session_token",sessionToken);
    return this.httpClient
      .post(this.url, formData).subscribe({
        next: (response) => {
          let resultValidation = this.processResponse(response);
          if (resultValidation == 0) {
            sessionStorage.removeItem('session_token'); //Delete the token
            this.router.navigate(['/login']); //Navigate to login
          }
        },
        error: (error) => console.log(error)
      });
  }

  /**
   * Process if the response is positive or negative
   *
   * @param response a JSON object with a int number
   * @returns bool depending of the result
   */
  processResponse(response: Object) {
    let result = 0;
    if (response == 0 || response == 1) {
      result = parseInt(response.toString())
    }
    return result;
  }
}
