import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  private urlAdminCommunityControllerInsertInvitation:string = ADDRESS_SERVER + "AdminCommunityController/insertInvitation.php";
  private urlAdminCommunityControllerSelectInvitations:string = ADDRESS_SERVER + "AdminCommunityController/selectInvitations.php";
  private urlAdminCommunityControllerRemoveRequest:string = ADDRESS_SERVER + "AdminCommunityController/removeRequest.php";
  private urlAdminCommunityControllerAcceptRequest:string = ADDRESS_SERVER + "AdminCommunityController/acceptRequest.php";
  private urlAdminCommunityControllerRemoveInvitation:string = ADDRESS_SERVER + "AdminCommunityController/removeInvitation.php";

  constructor(private httpClient: HttpClient) { }

  /**
   * Send a new invitation to one user
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @param idUser id of the user who is invited
   * @returns Observable with data
   */
  insertInvitation(sessionToken: string, idCommunity: number, idUser: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("id_user",idUser.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerInsertInvitation,formData);
  }

  /**
   * Select all invitations and requests by a community
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with data
   */
  selectInvitations(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerSelectInvitations,formData);
  }

  /**
   * Remove a incoming request
   *
   * @param sessionToken user session token
   * @param idUser id of the user who sent the request
   * @param idCommunity community id
   * @returns Observable with data
   */
  removeRequest(sessionToken: string, idUser: number, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_user",idUser.toString());
    formData.append("id_community",idCommunity.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerRemoveRequest, formData);
  }

  /**
   * Accept a incoming request
   *
   * @param sessionToken user session token
   * @param idUser id of the user who sent the request
   * @param idCommunity community id
   * @returns Observable with data
   */
  acceptRequest(sessionToken: string, idUser: number, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_user",idUser.toString());
    formData.append("id_community",idCommunity.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerAcceptRequest, formData);
  }

  /**
   * Remove a invitation to one user
   *
   * @param sessionToken user session token
   * @param idUser id of the user who is invited
   * @param idCommunity community id
   * @returns Observable with data
   */
  removeInvitation(sessionToken: string, idUser: number, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_user",idUser.toString());
    formData.append("id_community",idCommunity.toString());
    return this.httpClient.post(this.urlAdminCommunityControllerRemoveInvitation, formData);
  }
}
