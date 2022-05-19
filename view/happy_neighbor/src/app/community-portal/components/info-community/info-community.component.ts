import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';
import { CommunityService } from '../../services/community.service';
import { FormGroup, FormControl, Validators} from '@angular/forms';
import { AdminService } from '../../services/admin.service';


@Component({
  selector: 'app-info-community',
  templateUrl: './info-community.component.html',
  styleUrls: ['./info-community.component.scss']
})
export class InfoCommunityComponent implements OnInit {

  @Input() idCommunity: number = 0;
  sessionToken:string|null = null;
  sendedInvitations:any[] = [];
  receivedRequests:any[] = [];
  isAdmin:boolean = false;
  users:any[] = [];

  formInviteUser = new FormGroup({
    userId: new FormControl('', [
      Validators.required,
      Validators.pattern(/\d+/)
    ])
  });

  constructor(private communityService: CommunityService, private adminService: AdminService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
    this.selectCommunityUsers();
    this.selectInvitations();
  }

  get formControlsInsertInvitation() {
    return this.formInviteUser.controls;
  }

  /**
   * Select all users in the community
   */
  selectCommunityUsers() {
    if (this.sessionToken != null && this.idCommunity > 0) {
      let users:any[] = [];
      this.communityService.selectCommunityUsers(this.sessionToken, this.idCommunity).subscribe({
        next: (responseSelectCommunityUsers) => {
          let usersArray = Object.values(responseSelectCommunityUsers);
          usersArray.forEach( dataArray => {
            let userCommunity: UserCommunity = {
              id: dataArray[1]['id_user'],
              nameUser: dataArray[0]['name_user'],
              lastName: dataArray[0]['last_name'],
              profilePicture: dataArray[0]['profile_picture'],
              biography: dataArray[0]['biography'],
              isAdmin: dataArray[1]['is_admin'],
            }
            users[dataArray[1]['id_user']] = userCommunity;
          });
          this.users = users;
          this.checkIfAdmin();
        },
        error: (errorSelectCommunityUsers) => console.log(errorSelectCommunityUsers)
      });
    }
  }

  insertInvitation() {
    if (this.formInviteUser.status == "VALID" && this.sessionToken != null && this.idCommunity > 0) {
      this.adminService.insertInvitation(
        this.sessionToken,
        this.idCommunity,
        this.formInviteUser.value.userId
      ).subscribe({
        next: (responseInsertInvitation) => {
          console.log(responseInsertInvitation);
        },
        error: (errorInsertInvitation) => console.log(errorInsertInvitation)
      })
    }
  }

  checkIfAdmin() {
    if (this.sessionToken != null && this.idCommunity > 0) {
      this.communityService.checkIfAdmin(this.sessionToken,this.idCommunity).subscribe({
        next: (responseCheckIfAdmin) => {
          if (parseInt(responseCheckIfAdmin.toString()) == 1) {
            this.isAdmin = true
          }
        },
        error: (errorCheckIfAdmin) => console.log(errorCheckIfAdmin)

      })
    }
  }

  selectInvitations() {
    if (this.sessionToken != null && this.idCommunity > 0) {
      this.adminService.selectInvitations(this.sessionToken, this.idCommunity).subscribe({
        next: (responseSelectInvitations) => {
          this.receivedRequests = [];
          this.sendedInvitations = [];
          let arrayResponse = Object.values(responseSelectInvitations);
          console.log(arrayResponse);
          arrayResponse[0].forEach( (receivedRequest:any) => {
            let userCommunity: UserCommunity = {
              id: receivedRequest['id'],
              nameUser: receivedRequest['name_user'],
              lastName: receivedRequest['last_name'],
              profilePicture: receivedRequest['profile_picture'],
              biography: receivedRequest['biography'],
              isAdmin: receivedRequest['is_admin']
            }
            this.receivedRequests.push(userCommunity);
          });
          arrayResponse[1].forEach( (sendedInvitation:any) => {
            let userCommunity: UserCommunity = {
              id: sendedInvitation['id'],
              nameUser: sendedInvitation['name_user'],
              lastName: sendedInvitation['last_name'],
              profilePicture: sendedInvitation['profile_picture'],
              biography: sendedInvitation['biography'],
              isAdmin: sendedInvitation['is_admin']
            }
            this.sendedInvitations.push(userCommunity);
          });
        },
        error: (errorSelectInvitations) => console.log(errorSelectInvitations)
      })
    }
  }

  removeInvitation(idUser: number) {
    if (idUser > 0 && this.idCommunity > 0) {

    }
  }

  processRequest(idUser: number, isAccept: boolean = false) {
    if (idUser > 0 && this.idCommunity > 0) {

    }
  }
}
