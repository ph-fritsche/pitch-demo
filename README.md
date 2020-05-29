# pitch-demo


### src/Controller/FormTestController.php
```php
namespace App\Controller;
class FormTestController extends AbstractController
{
    /**
     * @Route("/formtest")
     */
    public function __invoke(
        Request $request
    ) {
        // ...
        $form = $this->createForm(FormType::class);

        // ...

        $form->handleRequest($request);

        // ...

        if ($form->isSubmitted() && $form->isValid()) {
            // action here
            // redirect or something
        }

        return $form;
    }
}
```

### src/Responder/FormHandler.php

```php
namespace App\Responder;
class FormHandler implements ResponseHandlerInterface
{
    // ...

    public function handleResponsePayload(
        ResponsePayloadEvent $event
    ) {
        /** @var Form */
        $form = $event->payload;

        $view = $form->createView();
        $transformResult = $this->liform->transform($view);

        // react_component() accepts arrays or json_decodes the props with $assoc=true
        // The latter produces invalid json-schemas when being re-encoded
        $formProps = (array)$transformResult;

        // set the name for the root view
        $formProps['name'] = $view->vars['full_name'];

        $statusCode = $form->isSubmitted() && !$form->isValid() ? 400 : 200;
        if ($this->isJsonPreferred($event->request)) {
            $event->payload = new JsonResponse($formProps, $statusCode);
        } else {
            $rendering = 'client_side';

            $event->payload = new Response($this->environment->render('form.html.twig', [
                'FormParams' => ['props' => $formProps, 'rendering' => $rendering],
            ]), $statusCode);
        }
    }
}
```

### templates/form.html.twig

```twig
{% block body %}
    {{ react_component('Form', FormParams) }}
{% endblock %}
```


### react/react.jsx

```jsx
const Form = (props) => {
    return <Liform {...props} renderField={(props) => {
        return <div style={{border: "1px solid grey", padding: "5px", margin: "5px"}}>
            <div><small>name: {props.name}</small></div>
            <div><code>schema: { JSON.stringify({widget: props.schema.widget, type: props.schema.type, format: props.schema.format}) }</code></div>
            { props.name &&
                <Field name={props.name} render={({input: {value}}) => 
                    <div><code>value: { JSON.stringify(value) }</code></div>
                }/>
            }
            <div style={{marginTop: "5px"}}>{ renderField(props) }</div>
        </div>
    }} onSubmit={(values) => {
        console.log(JSON.stringify(values, null, 2))
        fetch('', {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
            },
            body: JSON.stringify({[props.name]: values}),
        }).then(response => {
            console.warn(response.status)
            return response.text()
        }).then(bodyText => {
            console.log(bodyText)
        })
    }}>
        <header></header>
    </Liform>
}

ReactOnRails.register({ Form })
```
