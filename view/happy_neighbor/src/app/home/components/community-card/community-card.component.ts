import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { CommunityList } from '../../class/CommunityList';

@Component({
  selector: 'app-community-card',
  templateUrl: './community-card.component.html',
  styleUrls: ['./community-card.component.scss']
})
export class CommunityCardComponent implements OnInit {

  @Input() community: CommunityList|null = null;

  constructor() { }

  ngOnInit(): void {
  }

}
