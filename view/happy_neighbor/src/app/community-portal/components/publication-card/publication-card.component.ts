import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Publication } from '../../class/Publication';
import { UserCommunity } from '../../class/UserCommunity';
import { CommunityService } from '../../services/community.service';

@Component({
  selector: 'app-publication-card',
  templateUrl: './publication-card.component.html',
  styleUrls: ['./publication-card.component.scss']
})
export class PublicationCardComponent implements OnInit {

  @Input() publication: Publication|null = null;
  sessionToken:string|null = null;
  userPublication: UserCommunity|null = null;
  userPublicationCommented: UserCommunity|null = null;

  constructor(private route: ActivatedRoute, private communityService: CommunityService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe( (paramMap: any) => {
      const { params } = paramMap;
      let idCommunity = params.id;
      this.selectCommunityUser(idCommunity);
      if (this.publication?.commentTo != null) {
        this.selectCommunityUser(idCommunity,true);
      }
    })
  }

  selectCommunityUser(idCommunity: number, commented: boolean = false) {
    let idUser = this.publication?.idUser
    if (commented) {
      idUser = this.publication?.commentTo?.idUser;
    }
    if (this.sessionToken != null && idUser != null) {
      this.communityService.selectCommunityUser(this.sessionToken,idCommunity,idUser).subscribe({
        next: (responseSelectCommunityUser) => {
          let arrayCommunityUser = Object.assign(responseSelectCommunityUser);
          console.log(arrayCommunityUser);
          let userCommunity: UserCommunity = {
            nameUser: arrayCommunityUser['name_user'],
            lastName: arrayCommunityUser['last_name'],
            profilePicture: arrayCommunityUser['profile_picture'],
            isAdmin: arrayCommunityUser['is_admin'],
            biography: null
          }
          if (commented) {
            this.userPublicationCommented = userCommunity;
          } else {
            this.userPublication = userCommunity;
          }
        },
        error: (errorSelectCommunityUser) => (console.log(errorSelectCommunityUser))
      });
    }
  }

}
