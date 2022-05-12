import { Component, OnInit } from '@angular/core';
import { UserCommunitiesService } from '../../services/user-communities.service';
import { CommunityList } from '../../class/CommunityList';

@Component({
  selector: 'app-list-communities',
  templateUrl: './list-communities.component.html',
  styleUrls: ['./list-communities.component.scss'],
  providers: [UserCommunitiesService]
})
export class ListCommunitiesComponent implements OnInit {

  sessionToken:string|null = null;
  arrayCommunities:Array<CommunityList>= [];

  constructor(private userCommunitiesService: UserCommunitiesService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken != null) {
      this.downUserCommunities(this.sessionToken);
    }
  }

  /**
   * Check the user communities and save it
   *
   * @param sessionToken user session token
   */
  downUserCommunities(sessionToken: string) {
    this.userCommunitiesService.selectUserCommunities(sessionToken).subscribe({
      next: (responseSelectUserCommunities) => {
        let communityArray = Object.values(responseSelectUserCommunities);
        communityArray.forEach(communityData => {
          this.userCommunitiesService.selectNumberOfUsers(communityData,sessionToken).subscribe({
            next: (response) => this.arrayCommunities.push({
              id: communityData[0],
              nameCommunity: communityData[1],
              descriptionCommunity: communityData[2],
              isAdmin: communityData[3],
              totalUsers: parseInt(response.toString())
            }),
            error: (error) => console.log(error)
          });
        });
      },
      error: (errorSelectUserCommunities) => console.log(errorSelectUserCommunities)
    });
  }
}
