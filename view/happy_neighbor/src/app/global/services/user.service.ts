import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private urlUser:string = ADDRESS_SERVER + "UserController.php";

  constructor(private httpClient: HttpClient) { }

  select(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append('action','select');
    formData.append("session_token",sessionToken);
    return this.httpClient.post(this.urlUser, formData);
  }
}
