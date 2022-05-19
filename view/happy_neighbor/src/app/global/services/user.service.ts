import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private urlUserControllerSelect:string = ADDRESS_SERVER + "UserController/select.php";
  private urlUserControllerSelectRequests = ADDRESS_SERVER + "UserController/selectRequests.php";

  constructor(private httpClient: HttpClient) { }

  select(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    return this.httpClient.post(this.urlUserControllerSelect, formData);
  }

  selectRequests(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    return this.httpClient.post(this.urlUserControllerSelectRequests, formData);
  }
}
