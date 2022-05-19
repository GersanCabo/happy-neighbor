import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Community } from 'src/app/global/class/Community';
import { UserService } from 'src/app/global/services/user.service';
import { ValidateTokenService } from 'src/app/global/services/validate-token.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  sessionToken:string|null = null;
  receivedInvitations:any[] = [];
  sendedRequests:any[] = [];

  constructor(private userService: UserService, private validateTokenService: ValidateTokenService, private router: Router) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken != null) {
      this.validateToken(this.sessionToken);
      this.selectRequests(this.sessionToken);
    } else {
      this.router.navigate(['/login']);
    }
  }

 /**
  * Check if the session token is validate
  *
  * @param sessionToken user session token
  */
  validateToken(sessionToken: string) {
    this.validateTokenService.validateToken(sessionToken);
  }

  selectRequests(sessionToken: string) {
    this.userService.selectRequests(sessionToken).subscribe({
      next: (responseSelectRequests) => {
        this.sendedRequests = []
        this.receivedInvitations = []
        let arrayResponse = Object.values(responseSelectRequests);
        arrayResponse[0].forEach( (receivedInvitation:any) => {
          let community: Community = {
            id: receivedInvitation['id'],
            nameCommunity: receivedInvitation['name_community'],
            descriptionCommunity: receivedInvitation['description_community'],
            userCreatorId: 0,
            creationDate: ""
          }
          this.receivedInvitations.push(community);
        })
        arrayResponse[1].forEach( (sendedRequest:any) => {
          let community: Community = {
            id: sendedRequest['id'],
            nameCommunity: sendedRequest['name_community'],
            descriptionCommunity: sendedRequest['description_community'],
            userCreatorId: 0,
            creationDate: ""
          }
          this.sendedRequests.push(community);
        });
      },
      error: (errorSelectRequests) => console.log(errorSelectRequests)
    });
  }

}
