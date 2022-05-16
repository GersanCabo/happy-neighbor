import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CommunityService {

  private urlUserCommunityControllerSelect:string = ADDRESS_SERVER + "UserCommunityController/select.php";
  private urlUserCommunityControllerSelectCommunityUsers: string = ADDRESS_SERVER + "UserCommunityController/selectCommunityUsers.php";
  private urlUserCommunityControllerSelectCommunityUser: string = ADDRESS_SERVER + "UserCommunityController/selectCommunityUser.php";

  constructor(private httpClient: HttpClient) { }

  selectCommunity(sessionToken:string, idCommunity:number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelect, formData);
  }

  selectCommunityUsers(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelectCommunityUsers, formData);
  }

  selectCommunityUser(sessionToken: string, idCommunity: number, idUser: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("id_user",idUser.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelectCommunityUser, formData);
  }
}
