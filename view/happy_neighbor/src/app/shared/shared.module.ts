import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';
import { ReceivedInvitationComponent } from './components/received-invitation/received-invitation.component';
import { SendedRequestComponent } from './components/sended-request/sended-request.component';



@NgModule({
  declarations: [
    HeaderComponent,
    FooterComponent,
    ReceivedInvitationComponent,
    SendedRequestComponent
  ],
  imports: [
    CommonModule
  ],
  exports: [
    HeaderComponent,
    FooterComponent
  ]
})
export class SharedModule { }
