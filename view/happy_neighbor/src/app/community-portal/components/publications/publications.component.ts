import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators} from '@angular/forms';
import { Publication } from '../../class/Publication';
import { PublicationService } from '../../services/publication.service';

@Component({
  selector: 'app-publications',
  templateUrl: './publications.component.html',
  styleUrls: ['./publications.component.scss']
})
export class PublicationsComponent implements OnInit {

  @Input() idCommunity: number = 0;
  sessionToken:string|null = null;
  arrayPublications:Array<Publication> = [];

  formInsertPublication = new FormGroup({
    textPublication: new FormControl('',[
      Validators.required,
      Validators.maxLength(300),
      Validators.pattern(/.+/im)
    ])
  });

  constructor(private publicationService: PublicationService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    this.selectPublications();
  }

  get formControlsPublication() {
    return this.formInsertPublication.controls;
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
          this.arrayPublications.sort((a, b) => {
            let result:number = 0;
            if (Date.parse(a.datePublication) > Date.parse(b.datePublication)) {
              result = -1;
            } else if (Date.parse(a.datePublication) < Date.parse(b.datePublication)) {
              result = 1;
            }
            return result;
          })
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
          this.arrayPublications.sort((a, b) => {
            let result:number = 0;
            if (Date.parse(a.datePublication) > Date.parse(b.datePublication)) {
              result = -1;
            } else if (Date.parse(a.datePublication) < Date.parse(b.datePublication)) {
              result = 1;
            }
            return result;
          })
        },
        error: (errorSelectPublication) => console.log(errorSelectPublication)
      });
    }
  }

  insertPublication() {
    if (this.formInsertPublication.status == "VALID" && this.sessionToken != null) {
      this.publicationService.insertPublication(
        this.sessionToken,
        this.idCommunity,
        this.formInsertPublication.value.textPublication
      ).subscribe({
        next: (responseInsertPublication) => {
          console.log(responseInsertPublication);
        },
        error: (errorInsertPublication) => console.log(errorInsertPublication)
      })
    }
  }

}
