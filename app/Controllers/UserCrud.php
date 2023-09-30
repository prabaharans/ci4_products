<?php
namespace App\Controllers;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Config\Auth;
use CodeIgniter\Shield\Config\AuthSession;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Controller;
class UserCrud extends Controller
{
    private array $tables;

    public function __construct() {
        $authConfig   = config(Auth::class);
        $this->tables = $authConfig->tables;
    }

    // show users list
    public function index(){
        $userModel = new UserModel();
        $data['users'] = $userModel->orderBy('id', 'DESC')->findAll();
        return view('user_view', $data);
    }
    // add user form
    public function create(){
        return view('add_user');
    }

    // insert data
    public function store() {
        $users = $this->getUserProvider();
        $rules = $this->getValidationRules();
        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $allowedPostFields = array_keys($rules);
        $user              = $this->getUserEntity();
        $user->fill($this->request->getPost($allowedPostFields));

        if ($user->username === null) {
            $user->username = null;
        }
        if($user->name) {
            $user->identities[0]->name = $user->name;
        }
        try {
            $users->save($user);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }
        return $this->response->redirect(site_url('/users-list'));
    }
    // show single user
    public function singleUser($id = null){
        $userModel = new UserModel();
        $data['user_obj'] = $user_obj = $userModel->where('id', $id)->first();
        return view('edit_user', $data);
    }
    // update user data
    public function update(){
        $id = $this->request->getVar('id');
        $identities_id = $this->request->getVar('identities_id');
        $users = $this->getUserProvider();
        $rules = $this->getValidationRulesForUpdate($id, $identities_id);
        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $allowedPostFields = array_keys($rules);
        $user              = $this->getUserEntity();
        $userIdentity      = $this->getEmailIdentity();
        $user->fill($this->request->getPost($allowedPostFields));
        $userIdentity->fill($this->request->getPost($allowedPostFields));

        if ($user->username === null) {
            $user->username = null;
        }

        $identityModel = model(UserIdentityModel::class);
        // $credentials = [
        //     'email'    => $user->email,
        //     'password' => '',
        //     'name' => $user->name
        // ];
        // $identityModel->createEmailIdentity($this, $credentials);

        if (! empty($userIdentity->email)) {
            $userIdentity->secret = $userIdentity->email;
            unset($userIdentity->email);
        }

        try {
            $user->id = $id;
            $users->update($id, $user);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }
        try {
            $userIdentity->id = $identities_id;
            $identityModel->update($identities_id, $userIdentity);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }
        return $this->response->redirect(site_url('/users-list'));
    }

    // delete user
    public function delete($id = null){
        $userModel = new UserModel();
        $data['user'] = $userModel->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('/users-list'));
    }

    protected function getUserProvider(): UserModel
    {
        $provider = model(setting('Auth.userProvider'));

        assert($provider instanceof UserModel, 'Config Auth.userProvider is not a valid UserProvider.');

        return $provider;
    }

    protected function getValidationRulesForUpdate($id, $identities_id): array
    {
        $registrationUsernameRules = array_merge(
            config(AuthSession::class)->usernameValidationRules,
            [sprintf('is_unique[%s.username,id,'.$id.']', $this->tables['users'])]
        );
        $registrationEmailRules = array_merge(
            config(AuthSession::class)->emailValidationRules,
            [sprintf('is_unique[%s.secret,id,'.$identities_id.']', $this->tables['identities'])]
        );

        return setting('Validation.registration') ?? [
            'username' => [
                'label' => 'Auth.username',
                'rules' => $registrationUsernameRules,
            ],
            'email' => [
                'label' => 'Auth.email',
                'rules' => $registrationEmailRules,
            ]
        ];
    }
    protected function getValidationRules(): array
    {
        $registrationUsernameRules = array_merge(
            config(AuthSession::class)->usernameValidationRules,
            [sprintf('is_unique[%s.username]', $this->tables['users'])]
        );
        $registrationEmailRules = array_merge(
            config(AuthSession::class)->emailValidationRules,
            [sprintf('is_unique[%s.secret]', $this->tables['identities'])]
        );

        return setting('Validation.registration') ?? [
            'username' => [
                'label' => 'Auth.username',
                'rules' => $registrationUsernameRules,
            ],
            'email' => [
                'label' => 'Auth.email',
                'rules' => $registrationEmailRules,
            ],
            'password' => [
                'label'  => 'Auth.password',
                'rules'  => 'required|' . Passwords::getMaxLengthRule() . '|strong_password[]',
                'errors' => [
                    'max_byte' => 'Auth.errorPasswordTooLongBytes',
                ],
            ],
            'password_confirm' => [
                'label' => 'Auth.passwordConfirm',
                'rules' => 'required|matches[password]',
            ],
        ];
    }

    protected function getUserEntity(): User
    {
        return new User();
    }

    public function getEmailIdentity(): ?UserIdentity
    {
        return new UserIdentity();
    }
}