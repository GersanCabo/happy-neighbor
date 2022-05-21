import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'shortenText'
})
export class ShortenTextPipe implements PipeTransform {

  /**
   * Reduces a text to 40 characters and adds ellipses at the end (if the text exceeds 40 characters)
   */
  transform(value: any, ...args: unknown[]): unknown {
    let result = value;
    if (typeof value != "string") {
      result = "";
    } else if (value.length > 40) {
      result = value.slice(0,40) + "...";
    }
    return result;
  }

}
