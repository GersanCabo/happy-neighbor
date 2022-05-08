import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';
import { CommunityList } from '../class/CommunityList';


@Injectable({
  providedIn: 'root'
})
export class UserCommunitiesService {

  private urlUser:string = ADDRESS_SERVER + "UserController.php";
  private urlCommunity:string = ADDRESS_SERVER + "UserCommunityController.php";

  constructor(private httpClient: HttpClient) { }

  /**
   * Select the user communities
   *
   * @param sessionToken user session token
   * @returns Observable with the petition
   */
  selectUserCommunities(sessionToken:string) {
    let formData: FormData = new FormData();
    formData.append('action','selectUserCommunities');
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
    formData.append('action','selectNumberOfUsers');
    formData.append("session_token",sessionToken);
    formData.append('id',communityData[0].toString());
    return this.httpClient
    .post(this.urlCommunity, formData);
  }
}
