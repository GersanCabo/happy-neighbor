import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/address-server';
import { HttpClient } from '@angular/common/http';
import { CommunityList } from '../class/CommunityList';


@Injectable({
  providedIn: 'root'
})
export class UserCommunitiesService {

  private urlUser:string = ADDRESS_SERVER + "UserController.php";
  private urlCommunity:string = ADDRESS_SERVER + "UserCommunityController.php";

  constructor(private httpClient: HttpClient) { }

  selectUserCommunities(sessionToken:string) {
    let formData: FormData = new FormData();
    let arrayCommunities: Array<CommunityList> = []
    formData.append('action','selectUserCommunities');
    formData.append("session_token",sessionToken);
    this.httpClient
    .post(this.urlUser, formData)
    .subscribe({
      next: (response) => arrayCommunities = this.downCommunities(response, sessionToken),
      error: (error) => console.log(error)
    });
    return arrayCommunities;
  }

  private downCommunities(jsonUserCommunities: Object, sessionToken: string) {
    let communityArray = Object.values(jsonUserCommunities);
    let arrayCommunities: Array<CommunityList> = [];
    communityArray.forEach(communityData => {
      let formData: FormData = new FormData();
      formData.append('action','selectNumberOfUsers');
      formData.append("session_token",sessionToken);
      formData.append('id',communityData[0].toString());
      this.httpClient
      .post(this.urlCommunity, formData)
      .subscribe({
        next: (response) => arrayCommunities.push({
          id: communityData[0],
          nameCommunity: communityData[1],
          isAdmin: communityData[2],
          totalUsers: parseInt(response.toString())
        }),
        error: (error) => console.log(error)
      });
      //console.log(communityData[0] + "-" + communityData[1] + "-" + communityData[2]);
    });
    return arrayCommunities;
  }
}
