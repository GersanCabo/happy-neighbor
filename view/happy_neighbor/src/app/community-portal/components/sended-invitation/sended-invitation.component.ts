import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-sended-invitation',
  templateUrl: './sended-invitation.component.html',
  styleUrls: ['./sended-invitation.component.scss']
})
export class SendedInvitationComponent implements OnInit {

  @Input() sendedInvitation: UserCommunity = {
    id: 0,
    nameUser: "",
    lastName: null,
    profilePicture:null,
    biography: null,
    isAdmin: false
  };
  @Input() idCommunity: number = 0;
  sessionToken:string|null = null;
  removed: boolean = false;

  constructor(private adminService: AdminService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  /**
   * Remove a sent invitation
   *
   * @param idUser id of the user to the invitation was sent
   */
  removeInvitation(idUser: number) {
    if (this.sessionToken != null && idUser > 0 && this.idCommunity > 0) {
      this.adminService.removeInvitation(
        this.sessionToken,
        idUser,
        this.idCommunity
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
