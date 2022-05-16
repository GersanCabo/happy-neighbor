import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';
import { CommunityService } from '../../services/community.service';

@Component({
  selector: 'app-info-community',
  templateUrl: './info-community.component.html',
  styleUrls: ['./info-community.component.scss']
})
export class InfoCommunityComponent implements OnInit {

  @Input() idCommunity: number = 0;
  sessionToken:string|null = null;
  users:any[] = [];

  constructor(private communityService: CommunityService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  selectCommunityUsers() {
    if (this.sessionToken != null && this.idCommunity > 0) {
      let users:any[] = [];
      this.communityService.selectCommunityUsers(this.sessionToken, this.idCommunity).subscribe({
        next: (responseSelectCommunityUsers) => {
          let usersArray = Object.values(responseSelectCommunityUsers);
          usersArray.forEach( dataArray => {
            let userCommunity: UserCommunity = {
              nameUser: dataArray[0]['name_user'],
              lastName: dataArray[0]['last_name'],
              profilePicture: dataArray[0]['profile_picture'],
              biography: dataArray[0]['biography'],
              isAdmin: dataArray[1]['is_admin'],
            }
            users[dataArray[1]['id_user']] = userCommunity;
          });
          this.users = users;
        },
        error: (errorSelectCommunityUsers) => console.log(errorSelectCommunityUsers)
      });
    }
  }
}
