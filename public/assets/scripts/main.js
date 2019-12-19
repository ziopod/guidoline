
main();

function main() {

  const baseURL = `${document.location.origin}/action/`;
  const elements = document.querySelectorAll('.ajax-action');
  const titles = {
    'member_set_volunteer' : {
      is_active : 'Marquer comme bénévole',
      is_inactive : 'Marquer comme non-bénévole',
    }
  };

  elements.forEach(element => {
    element.addEventListener('click', event => {
      const action_do = element.getAttribute('data-action_do');
      const action_id = element.getAttribute('data-action_id');
      const request = `${baseURL}${action_do}/${action_id}`;
      const xhr = new XMLHttpRequest();

      xhr.onload = () => {
        if (xhr.status !== 200)
        {
          console.log(`Request ${request} failed! (status ${xhr.status})`);
        }
        else
        {
          if (element.classList.contains('is-active'))
          {
            element.classList.remove('is-active');
            element.classList.add('is-inactive');
            element.title = titles[action_do].is_inactive;
          }
          else
          {
            element.classList.remove('is-inactive');
            element.classList.add('is-active');
            element.title = titles[action_do].is_active;
          }

        }
      }

      xhr.open('GET', request);
      xhr.send();
      return false;
    })
  });
}
