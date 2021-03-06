import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CommunityPageComponent } from './components/community-page/community-page.component';
import { SharedModule } from '../shared/shared.module';
import { PublicationsComponent } from './components/publications/publications.component';
import { VotesComponent } from './components/votes/votes.component';
import { InfoCommunityComponent } from './components/info-community/info-community.component';
import { PublicationCardComponent } from './components/publication-card/publication-card.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ReceivedRequestComponent } from './components/received-request/received-request.component';
import { SendedInvitationComponent } from './components/sended-invitation/sended-invitation.component';
import { UserListCardComponent } from './components/user-list-card/user-list-card.component';



@NgModule({
  declarations: [
    CommunityPageComponent,
    PublicationsComponent,
    VotesComponent,
    InfoCommunityComponent,
    PublicationCardComponent,
    ReceivedRequestComponent,
    SendedInvitationComponent,
    UserListCardComponent
  ],
  imports: [
    CommonModule,
    SharedModule,
    FormsModule,
    ReactiveFormsModule
  ]
})
export class CommunityPortalModule { }
