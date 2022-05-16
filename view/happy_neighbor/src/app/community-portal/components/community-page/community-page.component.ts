import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Community } from '../../class/Community';
import { CommunityService } from '../../services/community.service';

@Component({
  selector: 'app-community-page',
  templateUrl: './community-page.component.html',
  styleUrls: ['./community-page.component.scss']
})
export class CommunityPageComponent implements OnInit {

  sessionToken:string|null = null;
  community: Community|null = null;
  optionSection: number = 1;

  constructor(private route:ActivatedRoute, private communityService: CommunityService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe( (paramMap: any) => {
      const { params } = paramMap;
      let idCommunity = params.id;
      this.selectCommunity(idCommunity);
    })
  }

  selectCommunity(idCommunity:any) {
    if (this.sessionToken != null && parseInt(idCommunity) > 0) {
      this.communityService.selectCommunity(this.sessionToken, idCommunity).subscribe({
        next: (responseSelectCommunity) => {
          let communityArray = Object.values(responseSelectCommunity);
          let community: Community = {
            id: communityArray[0],
            nameCommunity: communityArray[1],
            descriptionCommunity: communityArray[2],
            userCreatorId: communityArray[3],
            creationDate: communityArray[4]
          }
          this.community = community;
        },
        error: (errorSelectCommunity) => console.log(errorSelectCommunity)
      });
    }
  }
}
