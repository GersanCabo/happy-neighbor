import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { Publication } from '../../class/Publication';
import { CommunityService } from '../../services/community.service';
import { PublicationService } from '../../services/publication.service';

@Component({
  selector: 'app-publications',
  templateUrl: './publications.component.html',
  styleUrls: ['./publications.component.scss']
})
export class PublicationsComponent implements OnInit {

  @Input() idCommunity: number = 0;
  sessionToken:string|null = null;
  arrayPublications:Array<Publication> = []

  constructor(private publicationService: PublicationService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    this.selectPublications();
  }

  /**
   * Select a number of community publications
   */
  selectPublications() {
    if (this.sessionToken != null && this.idCommunity > 0) {
      this.publicationService.selectPublications(this.sessionToken,this.idCommunity).subscribe({
        next: (responseSelectPublications) => {
          let publicationsArray = Object.values(responseSelectPublications);
          publicationsArray[0].forEach( (publicationData: any) => {
            if (publicationData['comment_to']) {
              this.selectPublicationCommented(publicationData)
            } else {
              let publication: Publication = {
                id: publicationData['id'],
                textPublication: publicationData['text_publication'],
                likes: publicationData['likes'],
                datePublication: publicationData['date_publication'],
                commentTo: null,
                idUser: publicationData['id_user']
              }
              this.arrayPublications.push(publication);
            }
          });
        },
        error: (errorSelectPublications) => console.log(errorSelectPublications)
      });
    }
  }

  /**
   * Select the text of the commented publication
   *
   * @param publicationData main publication
   */
  selectPublicationCommented(publicationData:any):void {
    if (this.sessionToken != null && this.idCommunity > 0) {
      this.publicationService.selectPublication(this.sessionToken,this.idCommunity,publicationData['comment_to']).subscribe({
        next: (responseSelectPublication) => {
          let publicationCommentedArray = Object.assign(responseSelectPublication);
          let publicationCommented: Publication = {
            id: publicationCommentedArray['id'],
            textPublication: publicationCommentedArray['text_publication'],
            likes: publicationCommentedArray['likes'],
            datePublication: publicationCommentedArray['date_publication'],
            commentTo: null,
            idUser: publicationCommentedArray['id_user']
          }
          let publication: Publication = {
            id: publicationData['id'],
            textPublication: publicationData['text_publication'],
            likes: publicationData['likes'],
            datePublication: publicationData['date_publication'],
            commentTo: publicationCommented,
            idUser: publicationData['id_user']
          }
          this.arrayPublications.push(publication);
        },
        error: (errorSelectPublication) => console.log(errorSelectPublication)
      });
    }
  }

}
