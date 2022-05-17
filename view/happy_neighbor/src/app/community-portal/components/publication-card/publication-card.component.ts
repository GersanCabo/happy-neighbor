import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators} from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Publication } from '../../class/Publication';
import { UserCommunity } from '../../class/UserCommunity';
import { CommunityService } from '../../services/community.service';
import { PublicationService } from '../../services/publication.service';

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
  idCommunity: number = 0;

  formInsertPublication = new FormGroup({
    textPublication: new FormControl('',[
      Validators.required,
      Validators.maxLength(300),
      Validators.pattern(/.+/im)
    ])
  });

  constructor(private route: ActivatedRoute, private communityService: CommunityService, private publicationService: PublicationService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe( (paramMap: any) => {
      const { params } = paramMap;
      this.idCommunity = params.id;
      this.selectCommunityUser(this.idCommunity);
      if (this.publication?.commentTo != null) {
        this.selectCommunityUser(this.idCommunity,true);
      }
    })
  }

  get formControlsPublication() {
    return this.formInsertPublication.controls;
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
          if (idUser != undefined) {
            let userCommunity: UserCommunity = {
              id: idUser,
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
          }
        },
        error: (errorSelectCommunityUser) => (console.log(errorSelectCommunityUser))
      });
    }
  }

  insertPublication() {
    if (this.formInsertPublication.status == "VALID" && this.sessionToken != null && this.idCommunity > 0) {
      this.publicationService.insertPublication(
        this.sessionToken,
        this.idCommunity,
        this.formInsertPublication.value.textPublication,
        this.publication?.id
      ).subscribe({
        next: (responseInsertPublication) => {
          console.log(responseInsertPublication);
        },
        error: (errorInsertPublication) => console.log(errorInsertPublication)
      })
    }
  }

}
