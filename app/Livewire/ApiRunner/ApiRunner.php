<?php

namespace App\Livewire\ApiRunner;

use App\Models\{Workspace, ApiCollection, ApiRequest};
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;

class ApiRunner extends Component
{
    use WithFileUploads;

    public $workspace;
    public $selectedCollection = null;
    public $selectedRequest = null;
    public $response = null;
    public $loading = false;

    #[Url(as: 'request')]
    public $activeRequestId = null;

    public $openCollectionIds = [];

    // Request Editor State
    public $activeTab = 'params';
    public $requestMethod = 'GET';
    public $requestUrl = '';
    public $requestHeaders = [];
    public $requestQueryParams = [];
    public $requestBodyType = 'none';
    public $requestBody = '';
    public $requestFormData = [];
    public $requestFormUrlEncoded = [];
    public $requestAuthType = 'none';
    public $requestAuthData = [];

    // CRUD States
    public $showCreateCollectionModal = false;
    public $showCreateRequestModal = false;
    public $showDeleteModal = false;
    public $newItemName = '';
    public $targetParentId = null;
    public $itemToDeleteType = null;
    public $itemToDeleteId = null;

    protected $rules = [
        'newItemName' => 'required|min:1|max:255',
    ];

    public function openCreateCollectionModal($parentId = null)
    {
        $this->resetValidation();
        $this->newItemName = '';
        $this->targetParentId = $parentId;
        $this->showCreateCollectionModal = true;
    }

    public function createCollection()
    {
        $this->validate();

        ApiCollection::create([
            'workspace_id' => $this->workspace->id,
            'parent_id' => $this->targetParentId,
            'name' => $this->newItemName,
            'order' => 0,
        ]);

        $this->showCreateCollectionModal = false;
        $this->refreshWorkspace();
    }

    public function openCreateRequestModal($collectionId = null)
    {
        $this->resetValidation();
        $this->newItemName = '';
        $this->targetParentId = $collectionId;
        $this->showCreateRequestModal = true;
    }

    public function createRequest()
    {
        $this->validate();

        $request = ApiRequest::create([
            'workspace_id' => $this->targetParentId ? null : $this->workspace->id,
            'collection_id' => $this->targetParentId,
            'name' => $this->newItemName,
            'method' => 'GET',
            'url' => '',
            'body_type' => 'none',
        ]);

        $this->showCreateRequestModal = false;
        $this->refreshWorkspace();
        $this->selectRequest($request->id);
    }

    public function confirmDelete($type, $id)
    {
        $this->itemToDeleteType = $type;
        $this->itemToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteItem()
    {
        $shouldRedirect = false;

        if ($this->itemToDeleteType === 'collection') {
            if ($this->selectedRequest && in_array($this->itemToDeleteId, $this->openCollectionIds)) {
                $shouldRedirect = true;
            }

            if ($this->selectedRequest && $this->selectedRequest->collection_id == $this->itemToDeleteId) {
                $shouldRedirect = true;
            }

            ApiCollection::findOrFail($this->itemToDeleteId)->delete();

        } elseif ($this->itemToDeleteType === 'request') {
            if ($this->selectedRequest && $this->selectedRequest->id == $this->itemToDeleteId) {
                $shouldRedirect = true;
            }

            ApiRequest::findOrFail($this->itemToDeleteId)->delete();
        }

        $this->showDeleteModal = false;

        if ($shouldRedirect) {
            return redirect()->route('api-runner.workspace', $this->workspace->id);
        }

        $this->refreshWorkspace();
    }

    public function refreshWorkspace()
    {
        $this->workspace->refresh();
        $this->mount($this->workspace->id);
    }

    public function mount($workspace, $request = null, $collection = null)
    {
        $this->workspace = Workspace::with([
            'rootCollections.requests',
            'rootCollections.children.requests',
            'rootRequests'
        ])->findOrFail($workspace);

        if (!$this->workspace->isMember(auth()->id()) && $this->workspace->type !== 'public') {
            abort(403);
        }

        if ($request) {
            $this->selectRequest($request, $collection);
        } elseif ($this->activeRequestId) {
            $this->selectRequest($this->activeRequestId);
        }
    }

    public function selectRequest($requestId, $collectionId = null)
    {
        if ($collectionId) {
            $this->selectedCollection = ApiCollection::with('requests')->findOrFail($collectionId);
            $this->selectedRequest = $this->selectedCollection->requests->where('id', $requestId)->first();
        } else {
            $this->selectedRequest = ApiRequest::with('collection')->findOrFail($requestId);
        }

        $this->activeRequestId = $requestId;
        $this->calculateOpenCollections();
        $this->populateRequestState();
    }

    public function populateRequestState()
    {
        if (!$this->selectedRequest) return;

        $this->requestMethod = $this->selectedRequest->method;
        $this->requestUrl = $this->selectedRequest->url;
        $this->requestHeaders = is_array($this->selectedRequest->headers) ? $this->selectedRequest->headers : [];
        $this->requestQueryParams = is_array($this->selectedRequest->query_params) ? $this->selectedRequest->query_params : [];
        $this->requestBodyType = $this->selectedRequest->body_type;
        $this->requestBody = $this->selectedRequest->body;
        $this->requestFormData = is_array($this->selectedRequest->form_data) ? $this->selectedRequest->form_data : [];
        $this->requestFormUrlEncoded = is_array($this->selectedRequest->form_urlencoded_data) ? $this->selectedRequest->form_urlencoded_data : [];
        $this->requestAuthType = $this->selectedRequest->auth_type;
        $this->requestAuthData = is_array($this->selectedRequest->auth_data) ? $this->selectedRequest->auth_data : [];
        $this->response = $this->selectedRequest->last_response;
    }

    public function saveRequest()
    {
        if (!$this->selectedRequest) return;

        $this->selectedRequest->update([
            'method' => $this->requestMethod,
            'url' => $this->requestUrl,
            'headers' => $this->requestHeaders,
            'query_params' => $this->requestQueryParams,
            'body_type' => $this->requestBodyType,
            'body' => $this->requestBody,
            'form_data' => $this->requestFormData,
            'form_urlencoded_data' => $this->requestFormUrlEncoded,
            'auth_type' => $this->requestAuthType,
            'auth_data' => $this->requestAuthData,
        ]);
    }

    public function addHeader()
    {
        $this->requestHeaders[] = ['key' => '', 'value' => '', 'description' => '', 'enabled' => true];
    }

    public function removeHeader($index)
    {
        unset($this->requestHeaders[$index]);
        $this->requestHeaders = array_values($this->requestHeaders);
    }

    public function addQueryParam()
    {
        $this->requestQueryParams[] = ['key' => '', 'value' => '', 'description' => '', 'enabled' => true];
    }

    public function removeQueryParam($index)
    {
        unset($this->requestQueryParams[$index]);
        $this->requestQueryParams = array_values($this->requestQueryParams);
    }

    public function addFormData()
    {
        $this->requestFormData[] = ['key' => '', 'value' => '', 'type' => 'text', 'file' => null, 'enabled' => true];
    }

    public function removeFormData($index)
    {
        unset($this->requestFormData[$index]);
        $this->requestFormData = array_values($this->requestFormData);
    }

    public function addFormUrlEncoded()
    {
        $this->requestFormUrlEncoded[] = ['key' => '', 'value' => '', 'enabled' => true];
    }

    public function removeFormUrlEncoded($index)
    {
        unset($this->requestFormUrlEncoded[$index]);
        $this->requestFormUrlEncoded = array_values($this->requestFormUrlEncoded);
    }

    public function calculateOpenCollections()
    {
        $this->openCollectionIds = [];

        if (!$this->selectedRequest || !$this->selectedRequest->collection_id) {
            return;
        }

        $collection = $this->selectedRequest->collection;
        while ($collection) {
            $this->openCollectionIds[] = $collection->id;
            $collection = $collection->parent;
        }
    }

    public function executeRequest()
    {
        if (!$this->selectedRequest) return;

        $this->saveRequest();
        $this->loading = true;
        $this->response = null;

        try {
            $startTime = microtime(true);
            $client = $this->buildHttpClient();
            $url = $this->buildRequestUrl();
            $httpResponse = $this->sendRequest($client, $url);
            $endTime = microtime(true);

            $this->response = [
                'status' => $httpResponse->status(),
                'headers' => $httpResponse->headers(),
                'body' => $httpResponse->body(),
                'time' => round(($endTime - $startTime) * 1000, 2),
                'size' => strlen($httpResponse->body()),
                'timestamp' => now()->toISOString(),
            ];

            $this->selectedRequest->update(['last_response' => $this->response]);

        } catch (\Throwable $e) {
            $this->response = [
                'error' => true,
                'message' => $e->getMessage(),
            ];

            $this->selectedRequest->update(['last_response' => $this->response]);

        } finally {
            $this->loading = false;
        }
    }

    protected function buildHttpClient()
    {
        $client = Http::timeout(30)->acceptJson();

        // Apply headers
        foreach ($this->requestHeaders as $header) {
            if (($header['enabled'] ?? true) && !empty($header['key'])) {
                $client->withHeaders([$header['key'] => $header['value']]);
            }
        }

        // Apply authentication
        if ($this->requestAuthType === 'bearer' && !empty($this->requestAuthData['token'])) {
            $client->withToken($this->requestAuthData['token']);
        } elseif ($this->requestAuthType === 'basic') {
            $client->withBasicAuth(
                $this->requestAuthData['username'] ?? '',
                $this->requestAuthData['password'] ?? ''
            );
        }

        return $client;
    }

    protected function buildRequestUrl(): string
    {
        $url = $this->requestUrl;
        $method = strtolower($this->requestMethod);

        // For non-GET methods, append query params to URL
        if ($method !== 'get') {
            $queryParams = $this->getEnabledParams($this->requestQueryParams);
            if (!empty($queryParams)) {
                $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($queryParams);
            }
        }

        return $url;
    }

    protected function sendRequest($client, string $url)
    {
        $method = strtolower($this->requestMethod);

        if ($method === 'get') {
            return $client->get($url, $this->getEnabledParams($this->requestQueryParams));
        }

        return match ($this->requestBodyType) {
            'form-data' => $this->sendMultipartRequest($client, $method, $url),
            'x-www-form-urlencoded' => $this->sendFormRequest($client, $method, $url),
            default => $this->sendJsonOrRawRequest($client, $method, $url),
        };
    }

    protected function sendMultipartRequest($client, string $method, string $url)
    {
        $client->asMultipart();
        $data = [];

        foreach ($this->requestFormData as $item) {
            if (!($item['enabled'] ?? true) || empty($item['key'])) continue;

            if ($item['type'] === 'file' && isset($item['file'])) {
                $client->attach(
                    $item['key'],
                    fopen($item['file']->getRealPath(), 'r'),
                    $item['file']->getClientOriginalName()
                );
            } else {
                $data[$item['key']] = $item['value'];
            }
        }

        return $client->$method($url, $data);
    }

    protected function sendFormRequest($client, string $method, string $url)
    {
        $client->asForm();
        $data = $this->getEnabledParams($this->requestFormUrlEncoded);
        return $client->$method($url, $data);
    }

    protected function sendJsonOrRawRequest($client, string $method, string $url)
    {
        $body = $this->requestBodyType === 'json'
            ? json_decode($this->requestBody, true)
            : $this->requestBody;

        return $client->$method($url, $body ?? []);
    }

    protected function getEnabledParams(array $params): array
    {
        $enabled = [];
        foreach ($params as $param) {
            if (($param['enabled'] ?? true) && !empty($param['key'])) {
                $enabled[$param['key']] = $param['value'];
            }
        }
        return $enabled;
    }

    public function render()
    {
        return view('livewire.api-runner.api-runner')
            ->layout('layouts.fullpage', ['title' => 'API Runner - ' . $this->workspace->name]);
    }
}
