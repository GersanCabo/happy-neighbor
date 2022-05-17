import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  private urlAdminCommunityControllerInsertInvitation:string = ADDRESS_SERVER + "AdminCommunityController/insertInvitation.php";
  private urlAdminCommunityControllerSelectInvitations:string = ADDRESS_SERVER + "AdminCommunityController/selectInvitations.php";

  constructor(private httpClient: HttpClient) { }

  insertInvitation(sessionToken: string, idCommunity: number, idUser: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("id_user",idUser.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerInsertInvitation,formData);
  }

  selectInvitations(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerSelectInvitations,formData);
  }
}
