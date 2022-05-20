import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';

@Component({
  selector: 'app-user-list-card',
  templateUrl: './user-list-card.component.html',
  styleUrls: ['./user-list-card.component.scss']
})
export class UserListCardComponent implements OnInit {

  @Input() user: UserCommunity = {
    id: 0,
    nameUser: "",
    lastName: "",
    profilePicture: "",
    biography: "",
    isAdmin: false
  }

  constructor() { }

  ngOnInit(): void {
  }

}
