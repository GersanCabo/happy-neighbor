import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from 'src/app/community-portal/class/UserCommunity';
import { Community } from 'src/app/global/class/Community';
import { UserService } from 'src/app/global/services/user.service';

@Component({
  selector: 'app-received-invitation',
  templateUrl: './received-invitation.component.html',
  styleUrls: ['./received-invitation.component.scss']
})
export class ReceivedInvitationComponent implements OnInit {

  @Input() receivedInvitation: Community = {
    id: 0,
    nameCommunity: "",
    descriptionCommunity: "",
    userCreatorId: 0,
    creationDate: ""
  };
  sessionToken: string|null = null;
  accepted: boolean = false;
  removed: boolean = false;

  constructor(private userService: UserService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  /**
   * Accept invitation sended by a community
   *
   * @param idCommunity community id
   */
  acceptInvitation(idCommunity: number) {
    if (this.sessionToken != null && idCommunity > 0) {
      this.userService.acceptInvitation(
        this.sessionToken,
        idCommunity
      ).subscribe({
        next: (responseAcceptInvitation) => {
          if (responseAcceptInvitation.toString() == '1') {
            this.accepted = true;
          }
        },
        error: (errorAcceptInvitation) => console.log(errorAcceptInvitation)
      });
    }
  }

  /**
   * Remove a invitation sended by a community
   *
   * @param idCommunity community id
   */
  removeInvitation(idCommunity: number) {
    if (this.sessionToken != null && idCommunity > 0) {
      this.userService.removeInvitation(
        this.sessionToken,
        idCommunity
      ).subscribe({
        next: (responseRemoveInvitation) => {
          if (responseRemoveInvitation.toString() == '1') {
            this.removed = true;
          }
        },
        error: (errorRemoveInvitation) => console.log(errorRemoveInvitation)
      });
    }
  }
}
