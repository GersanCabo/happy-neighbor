import { Component, OnInit } from '@angular/core';
import { UserCommunitiesService } from '../../services/user-communities.service';
import { Router } from '@angular/router';
import { ValidateTokenService } from 'src/app/global/services/validate-token.service';
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

  constructor(private router: Router, private userCommunitiesService: UserCommunitiesService, private validateTokenService: ValidateTokenService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken != null) {
      this.validateToken(this.sessionToken);
      this.downUserCommunities(this.sessionToken);
    } else {
      this.router.navigate(['/login']);
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
              isAdmin: communityData[2],
              totalUsers: parseInt(response.toString())
            }),
            error: (error) => console.log(error)
          });
        });
      },
      error: (errorSelectUserCommunities) => console.log(errorSelectUserCommunities)
    });;
  }

  /**
   * Check if the session token is validate
   *
   * @param sessionToken user session token
   */
  validateToken(sessionToken: string) {
    this.validateTokenService.validateToken(sessionToken);
  }
}
