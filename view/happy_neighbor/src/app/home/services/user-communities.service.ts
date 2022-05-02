import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/address-server';
import { HttpClient } from '@angular/common/http';


@Injectable({
  providedIn: 'root'
})
export class UserCommunitiesService {

  private urlUser:string = ADDRESS_SERVER + "UserController.php";

  constructor(private httpClient: HttpClient) { }

  selectUserCommunities(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append('action','selectUserCommunities');
    formData.append("session_token",sessionToken);
    return this.httpClient
    .post(this.urlUser, formData)
    .subscribe({
      next: (response) => console.log(response),
      error: (error) => console.log(error)
  });
  }
}
