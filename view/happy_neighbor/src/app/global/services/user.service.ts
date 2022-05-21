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

  /**
   * Select info of the user
   *
   * @param sessionToken user session token
   * @returns Observable with data
   */
  select(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    return this.httpClient.post(this.urlUserControllerSelect, formData);
  }

  /**
   * Select all request sended by the user and all invitations received
   *
   * @param sessionToken user session token
   * @returns Observable with data
   */
  selectRequests(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    return this.httpClient.post(this.urlUserControllerSelectRequests, formData);
  }

  /**
   * Accept the invitation received by a commmunity
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with data
   */
  acceptInvitation(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerAcceptInvitation, formData);
  }

  /**
   * Remove the invitation received by a community
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with data
   */
  removeInvitation(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerRemoveInvitation, formData);
  }

  /**
   * Remove a request sended bi the user to a community
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with data
   */
  removeRequest(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token", sessionToken);
    formData.append("id_community", idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerRemoveRequest, formData);
  }
}
