import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-received-request',
  templateUrl: './received-request.component.html',
  styleUrls: ['./received-request.component.scss']
})
export class ReceivedRequestComponent implements OnInit {

  @Input() receivedRequest: UserCommunity = {
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
  accepted: boolean = false;

  constructor(private adminService: AdminService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  removeRequest(idUser: number) {
    if (this.sessionToken != null && idUser > 0 && this.idCommunity > 0) {
      this.adminService.removeRequest(
        this.sessionToken,
        idUser,
        this.idCommunity
      ).subscribe({
        next: (responseRemoveRequest) => {
          if (responseRemoveRequest.toString() == '1') {
            this.removed = true;
          }
        },
        error: (errorRemoveRequest) => console.log(errorRemoveRequest)
      });
    }
  }

  acceptRequest(idUser: number) {
    if (this.sessionToken != null && idUser > 0 && this.idCommunity > 0) {
      this.adminService.acceptRequest(
        this.sessionToken,
        idUser,
        this.idCommunity
      ).subscribe({
        next: (responseAcceptRequest) => {
          if (responseAcceptRequest.toString() == '1') {
            this.accepted = true
          }
        },
        error: (errorAcceptRequest) => console.log(errorAcceptRequest)
      })
    }
  }
}
