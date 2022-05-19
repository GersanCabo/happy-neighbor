import { Component, OnInit } from '@angular/core';
import { UserCommunitiesService } from '../../services/user-communities.service';
import { CommunityList } from '../../class/CommunityList';
import { FormGroup, FormControl, Validators} from '@angular/forms';


@Component({
  selector: 'app-list-communities',
  templateUrl: './list-communities.component.html',
  styleUrls: ['./list-communities.component.scss'],
  providers: [UserCommunitiesService]
})
export class ListCommunitiesComponent implements OnInit {

  sessionToken:string|null = null;
  arrayCommunities:Array<CommunityList>= [];

  formSendRequest = new FormGroup({
    communityId: new FormControl('', [
      Validators.required,
      Validators.pattern(/\d+/)
    ])
  });

  constructor(private userCommunitiesService: UserCommunitiesService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken != null) {
      this.downUserCommunities(this.sessionToken);
    }
  }

  get formControlsInsertRequest() {
    return this.formSendRequest.controls;
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

  insertRequest() {
    if (this.formSendRequest.status == "VALID" && this.sessionToken != null)
    this.userCommunitiesService.insertRequest(
      this.sessionToken,
      this.formSendRequest.value.communityId
    ).subscribe({
      next: (responseInsertRequest) => {
        console.log(responseInsertRequest);
      },
      error: (errorInsertRequest) => console.log(errorInsertRequest)
    });
  }
}
