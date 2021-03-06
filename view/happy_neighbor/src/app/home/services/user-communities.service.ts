import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';


@Injectable({
  providedIn: 'root'
})
export class UserCommunitiesService {

  private urlUser:string = ADDRESS_SERVER + "UserController/selectUserCommunities.php";
  private urlCommunity:string = ADDRESS_SERVER + "UserCommunityController/selectNumberOfUsers.php";
  private urlUserControllerInsertRequest:string = ADDRESS_SERVER + "UserController/insertRequest.php";
  private urlUserCommunityControllerInsert:string = ADDRESS_SERVER + "UserCommunityController/insert.php";


  constructor(private httpClient: HttpClient) { }

  /**
   * Select the user communities
   *
   * @param sessionToken user session token
   * @returns Observable with the petition
   */
  selectUserCommunities(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    return this.httpClient
    .post(this.urlUser, formData);
  }

  /**
   * Seelct the number of users in a communities
   *
   * @param communityData array with the community data
   * @param sessionToken user session token
   * @returns Observable with the petition
   */
  selectNumberOfUsers(communityData: Array<any>, sessionToken: string) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append('id',communityData[0].toString());
    return this.httpClient
    .post(this.urlCommunity, formData);
  }

  /**
   * Send a new request to a community
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @returns Observable with the petition
   */
  insertRequest(sessionToken: string, idCommunity: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append('id_community',idCommunity.toString());
    return this.httpClient.post(this.urlUserControllerInsertRequest, formData);
  }

  /**
   * Create a new community
   *
   * @param sessionToken user session token
   * @param nameCommunity community name
   * @param descriptionCommunity community description (not required)
   * @returns Observable with the petition
   */
  createNewCommunity(sessionToken: string, nameCommunity: string, descriptionCommunity: string = "") {
    let formData: FormData = new FormData();
    formData.append("session_token", sessionToken);
    formData.append("name_community", nameCommunity);
    formData.append("description_community", descriptionCommunity);
    return this.httpClient.post(this.urlUserCommunityControllerInsert, formData);
  }
}
