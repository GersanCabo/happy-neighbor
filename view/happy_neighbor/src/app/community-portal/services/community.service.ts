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
  private urlUserCommunityControllerCheckIfAdmin: string = ADDRESS_SERVER + "UserCommunityController/checkIfAdmin.php";

  constructor(private httpClient: HttpClient) { }

  /**
   * Select a community data
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with the petition
   */
  selectCommunity(sessionToken:string, idCommunity:number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelect, formData);
  }

  /**
   * Select all users of a community
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with the petition
   */
  selectCommunityUsers(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelectCommunityUsers, formData);
  }

  /**
   * Select a community user
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @param idUser user id
   * @returns Observable with the petition
   */
  selectCommunityUser(sessionToken: string, idCommunity: number, idUser: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("id_user",idUser.toString());
    return this.httpClient.post(this.urlUserCommunityControllerSelectCommunityUser, formData);
  }

  /**
   * Check if the user has a role of admin
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with the petition
   */
  checkIfAdmin(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    return this.httpClient.post(this.urlUserCommunityControllerCheckIfAdmin, formData);
  }
}
