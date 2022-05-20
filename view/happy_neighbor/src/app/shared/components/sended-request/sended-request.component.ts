import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { Community } from 'src/app/global/class/Community';
import { UserService } from 'src/app/global/services/user.service';

@Component({
  selector: 'app-sended-request',
  templateUrl: './sended-request.component.html',
  styleUrls: ['./sended-request.component.scss']
})
export class SendedRequestComponent implements OnInit {

  @Input() sendedRequest: Community = {
    id: 0,
    nameCommunity: "",
    descriptionCommunity: "",
    userCreatorId: 0,
    creationDate: ""
  };
  sessionToken: string|null = null;
  removed: boolean = false;

  constructor(private userService: UserService) { }

  ngOnInit(): void {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  removeRequest(idCommunity: number) {
    if (this.sessionToken != null && idCommunity > 0) {
      this.userService.removeRequest(
        this.sessionToken,
        idCommunity
      ).subscribe({
        next: (responseRemoveRequest) => {
          if (responseRemoveRequest.toString() == '1') {
            this.removed = true;
          }
        },
        error: (errorRemoveRequest) => console.log(errorRemoveRequest)
      })
    }
  }

}
