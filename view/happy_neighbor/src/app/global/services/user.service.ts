import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private urlUserControllerSelect:string = ADDRESS_SERVER + "UserController/select.php";
  private urlUserControllerSelectRequests = ADDRESS_SERVER + "UserController/selectRequests.php";
  private urlUserControllerAcceptInvitation = ADDRESS_SERVER + "UserController/acceptInvitation.php";
  private urlUserControllerRemoveInvitation = ADDRESS_SERVER + "UserController/removeInvitation.php";
  private urlUserControllerRemoveRequest = ADDRESS_SERVER + "UserController/removeRequest.php";

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

  acceptInvitation(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerAcceptInvitation, formData);
  }

  removeInvitation(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerRemoveInvitation, formData);
  }

  removeRequest(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token", sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerRemoveRequest, formData);
  }
}
