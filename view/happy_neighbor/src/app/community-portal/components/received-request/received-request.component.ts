import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { UserCommunity } from '../../class/UserCommunity';

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

  constructor() { }

  ngOnInit(): void {
  }

  processRequest(idUser: number, isAccept: boolean = false) {
    if (idUser > 0 && this.idCommunity > 0) {

    }
  }
}
